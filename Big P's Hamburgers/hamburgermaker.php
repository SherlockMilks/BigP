<?php
include "hambi.php";
session_start();

if (isset($_POST["add"])){
    $alapanyagok=["Buci"];
    foreach ($_POST["alap"] as $nev => $menny){
        if ($menny != null){
            for ($i=0;$i<$menny;$i++){
                $alapanyagok[]=$nev;
            }
        }
    }
    sort($alapanyagok);
    $egyedi_hambi=new hambi("Egyedi hamburger","custom_hambi.png",$alapanyagok);
    $_SESSION["egyedi"]=$egyedi_hambi;
}


if (isset($_POST["kosar"])){
    if (isset($_SESSION["egyedi"])) {
        $_SESSION["kosar"][] = $_SESSION["egyedi"];
        unset($_SESSION["egyedi"]);
        header("Location: kosar.php");
    }
}

if (isset($_POST["torles"])){
    unset($_SESSION["egyedi"]);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/hambimaker.css">
    <link rel="icon" href="img/icon_image.png"/>
    <title>Hamburger készítő</title>
</head>
<body>
<nav>

    <div class="dropdown">
        <button class="dropdown-button">
            <img src="img/navbar/dropdown.png" alt="dropdown" id="dropdown-icon">
        </button>
        <div class="dropdown-content">
            <a href="index.php">Kezdőlap</a>
            <a href="menu.php">Menü</a>
            <a href="hamburgermaker.php" id="current">Egyedi hamburger</a>
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

<main>
    <!--rendelés menűrész-->
    <div class="bal textbox flex_box">
        <img src="img/hamburger/custom_hambi.png" alt="hambi" class="hambi_img">
        <div>
            <ul style="margin-left: 40px;">
                <?php
                    if (isset($_SESSION["egyedi"])){
                        $alapanyagok=$_SESSION["egyedi"]->getAlapanyagok();
                        $db=1;
                        for ($i=0;$i<count($alapanyagok);$i++){
                            if ($i != count($alapanyagok)-1 &&$alapanyagok[$i]==$alapanyagok[$i+1]){
                                $db++;
                            }else{
                                if ($db==1){
                                    echo "<li>".$alapanyagok[$i]."</li>";
                                }else{
                                    echo "<li>".$alapanyagok[$i]." X ".$db."</li>";
                                }
                                $db=1;
                            }
                        }
                    }else{
                        echo '<li style="list-style: none">Válassza ki, hogy miből mennyit kér, majd a</li>';
                        echo '<li style="list-style: none">  "hozzáad" gomb segítségével készítse el hamburgerét! </li>';
                    }

                ?>
            </ul>
        </div>
        <form action="hamburgermaker.php" method="post">
            <div class="button_container">
                <p>
                    <?php
                    if (isset($_SESSION["user"])){
                        if (isset($_SESSION["egyedi"])){
                            echo $_SESSION["egyedi"]->ar()."Ft";
                        }
                        echo '<input type="submit" name="torles" value="Ürítés" class="button_kosar">';
                        echo '<input type="submit" name="kosar" value="Kosárba!" class="button_kosar">';
                    }else{
                        echo "<p>A rendeléshez be kell jelentkeznie!</p>";
                    }

                    ?>
                </p>
            </div>
        </form>
    </div>
    <!--rendelés menűrész vége-->

    <div class="jobb textbox">
        <form action="hamburgermaker.php" method="post">
            <p style="text-align: center">Alapanyag lista:</p>

            <!--alapanyag felsorolás-->
            <div class="alapanyag">
                <p>Hamburger buci                   <!--label + adagmegadás-->
                    <label style="margin-left: 30px">300Ft / adag: <input disabled type="number" value="1" class="mennyiseg" name="alap[Buci]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Marhahús
                    <label style="margin-left: 30px">700Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Marhahús").'"';?>
                                                                          class="mennyiseg" name="alap[Marhahús]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Csirkehús
                    <label style="margin-left: 30px">750Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Csirkehús").'"';?>
                                                                          class="mennyiseg" name="alap[Csirkehús]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Bacon
                    <label style="margin-left: 30px">350Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Bacon").'"';?>
                                                                          class="mennyiseg" name="alap[Bacon]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Jégsaláta
                    <label style="margin-left: 30px">250Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Jégsaláta").'"';?>
                                                                          class="mennyiseg" name="alap[Jégsaláta]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Uborka
                    <label style="margin-left: 30px">200Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Uborka").'"';?>
                                                                          class="mennyiseg" name="alap[Uborka]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Paradicsom
                    <label style="margin-left: 30px">200Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Paradicsom").'"';?>
                                                                          class="mennyiseg" name="alap[Paradicsom]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Pirított hagyma
                    <label style="margin-left: 30px">300Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Pirított hagyma").'"';?>
                                                                          class="mennyiseg" name="alap[Pirított hagyma]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Lilahagyma
                    <label style="margin-left: 30px">200Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Lila hagyma").'"';?>
                                                                          class="mennyiseg" name="alap[Lila hagyma]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Vöröshagyma
                    <label style="margin-left: 30px">200Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Vörös hagyma").'"';?>
                                                                          class="mennyiseg" name="alap[Vörös hagyma]"></label>
                </p>
            </div>

            <!--alapanyag felsorolás még mindig-->

            <p style="text-align: center">Szószok:</p>

            <div class="alapanyag">
                <p>Hagymalekvár
                    <label style="margin-left: 30px">100Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Hagymalekvár").'"';?>
                                                                          class="mennyiseg" name="alap[Hagymalekvár]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>  Ketchup
                    <label style="margin-left: 30px">75Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Ketchup").'"';?>
                                                                         class="mennyiseg" name="alap[Ketchup]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p> Mustár
                    <label style="margin-left: 30px">75Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Mustár").'"';?>
                                                                         class="mennyiseg" name="alap[Mustár]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p>Hamburgerszósz
                    <label style="margin-left: 30px">100Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Hamburgerszósz").'"';?>
                                                                          class="mennyiseg" name="alap[Hamburgerszósz]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p> BBQ szósz
                    <label style="margin-left: 30px">100Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("BBQ szósz").'"';?>
                                                                          class="mennyiseg" name="alap[BBQ szósz]"></label>
                </p>
            </div>

            <div class="alapanyag">
                <p> Majonéz
                    <label style="margin-left: 30px">75Ft / adag: <input type="number" placeholder="0" <?php if (isset($_SESSION["egyedi"])) echo 'value="'.$_SESSION["egyedi"]->menny("Majonéz").'"';?>
                                                                         class="mennyiseg" name="alap[Majonéz]"></label>
                </p>
            </div>

            <input type="submit" name="add" value="Hozzáad!" class="button_kosar">

        </form>
        <!--alapanyag felsorolás vége-->
    </div>



</main>

</body>
</html>