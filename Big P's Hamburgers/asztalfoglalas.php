<?php
include "function/json_control.php";

session_start();
$hibak=[];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/asztalfoglalas.css">
    <link rel="icon" href="img/icon_image.png"/>
    <title>Asztalfoglalás</title>
</head>
<body>

<!--Navigáció-->
<nav>
    <div class="dropdown">
        <button class="dropdown-button">
            <img src="img/navbar/dropdown.png" alt="dropdown" id="dropdown-icon">
        </button>
        <div class="dropdown-content">
            <a href="index.php" id="current">Kezdőlap</a>
            <a href="menu.php">Menü</a>
            <a href="hamburgermaker.php">Egyedi hamburger</a>
            <a href="asztalfoglalas.php">Asztalfoglalás</a>
        </div>
    </div>
    <div id="regiszter">
        <a href="kosar.php"><img src="img/navbar/kosar.png" alt="kosar" class="icon"></a>
        <?php if (isset($_SESSION["user"])) {  ?>
            <a href="fiok.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } else { ?>
            <a href="login.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } ?>
    </div>
</nav>
<!--Navigáció vége-->

<!--Tartalom-->
<main>
    <h1>Asztalfoglalás</h1>

    <!--Szabályok-->
    <h2>Szabályok:</h2>
    <ul>
        <li>Az asztalfoglaláshoz be kell jelentkeznie!</li>
        <li>Egy asztal két órára lesz lefoglalva</li>
        <li>11 és 21 óra között van nyitva az étterem</li>
        <li>Egy vendég maximum három időpontot foglalhat le</li>
        <li>Ha a lefoglalt időponttól 30 percig nem érkezik meg, akkor a foglalását töröljük</li>
    </ul>
    <!--Szabályok vége-->

    <!--Keresés-->
    <form action="asztalfoglalas.php" method="post">
        <label>
            Időpont:
            <br>
            <input type="date" name="date"  required>
            <input style="width: 50px" type="number" name="date2" placeholder="óra" required>
        </label>
        <input type="submit" name="keres" value="keres">
    </form>
    <!--Keresés vége-->

    <!--Szabad helyek-->
    <form action="asztalfoglalas.php" method="post">
    <table>
        <?php

            if (isset($_POST["keres"])){
                if (!isset($_SESSION["user"])) {    //bejelentkezés vizsgálat
                    $hibak[]="Ehhez a funkcióhoz be kell jelentkeznie!";
                }
                else {
                    if (count($_SESSION["user"]["foglalasok"])==3){  //foglalás szám vizsgálat
                        $hibak[]="Maximum három időpontot foglalhat le!";
                    }

                    if(!preg_match('/^[0-9]{4}.(0[1-9]|1[0-2]).(0[1-9]|[12][0-9]|3[01]).?$/',$_POST["date"])){  //dátum formátumának ellenőrzése
                        $hibak[] = "A dátum formátuma nem megfelelő!";
                    }

                    if(!filter_var($_POST["date2"], FILTER_VALIDATE_INT)){  //int-é konvertálható-e a megadott óra
                        $hibak[] = "Az óra formátuma nem megfelelő!";
                    }

                    elseif ($_POST["date2"] < 11 || $_POST["date2"] > 20){ //helyes óra vizsgálat
                        $hibak[]="Az étterem ilyenkor nincs nyitva!";
                    }

                    if(!isset($_POST["date"]) || !isset($_POST["date2"])){ //ki lett-e töltve az adat
                        $hibak[]="Írj be minden adatot!";
                    }

                    if ($_POST["date"] < date("Y-m-d")){   //helyes dátum vizsgálat
                        $hibak[]="Erre a napra nem lehet foglalni!";
                    }



                    if(count($hibak)===0){
                        echo "<tr>";            //táblázat fejlécének írása
                        echo "<th>Asztal</th>";
                        echo "<th>Fő</th>";
                        echo "<th>Lefoglal</th>";
                        echo "</tr>";

                        $datumok=[  //óra elmentése egy tömbe
                            $_POST["date"]."-".$_POST["date2"],
                            $_POST["date"]."-".($_POST["date2"]-1)
                        ];
                        $_SESSION["datum"]=$datumok[0];  //óra elmentése későbbre
                        $asztalok=asztal_betolt("json/asztalok.json");

                        foreach ($asztalok as $asztal){
                            if($asztal["foglalasok"]==null || (!in_array($datumok[0],$asztal["foglalasok"]) && !in_array($datumok[1],$asztal["foglalasok"]))) { //üres asztal keresés
                                $id = $asztal["id"];
                                $helyek = $asztal["helyek"];
                                $foglalasok = $asztal["foglalasok"];

                                echo "<tr>";           //táblázat sor írása
                                echo "<td>$id </td>";
                                echo "<td>$helyek</td>";
                                echo "<td><label><input type='checkbox' name='$id'></label></td>";
                                echo "</tr>";
                            }
                        }
                    }
                }
            }

            //kiválasztott asztal lementése
            if (isset($_POST["lefoglal"])){

                $asztalok=asztal_betolt("json/asztalok.json");

                for ($i=1;$i<21;$i++){ //öszes kiválasztott asztal megkeresése
                    $t="T".$i;
                    if (isset($_POST["$t"])){
                        $asztalok[$i-1]["foglalasok"][$_SESSION["datum"]]=$_SESSION["user"]["username"];
                    }
                }

                $ment["asztalok"]=$asztalok;  //módosított asztalok lementése

                $ment=json_encode($ment,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                file_put_contents("json/asztalok.json",$ment);


                $fiokok = load_users("json/user.json");     //fiók lementése új adattal

                foreach ($fiokok["users"] as &$fiok){
                   if($fiok["username"]==$_SESSION["user"]["username"]){
                       if (!in_array($_SESSION["datum"],$fiok["foglalasok"])){
                       $fiok["foglalasok"][]=$_SESSION["datum"];
                       $_SESSION["user"]["foglalasok"]=$fiok["foglalasok"];
                       }
                       break;
                   }
                }

                $fiokok=json_encode($fiokok, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents("json/user.json",$fiokok);
                header("Location: asztalfoglalas.php");

            }
        ?>
    </table>
        <?php
            if (isset($_POST["keres"])) {
                foreach ($hibak as $hiba){
                    echo "<p class='hiba'>".$hiba."</p>";
                    echo "</br>";
            }
        }
        ?>
    <!--Szabad helyek vége-->

        <?php
            if(isset($_POST["keres"]) && count($hibak)===0){
                echo "<input class='lefoglal' type='submit' name='lefoglal' value='Lefoglalom!'>";
            }
        ?>
    </form>
</main>
<!--Tartalom vége-->

</body>
</html>