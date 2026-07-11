<?php
include "hambi.php";
session_start();

$uzenet="<p class='ureskosar'>A kosár üres!</p>";

if (isset($_SESSION["kosar"])) {
    for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {            //törlés
        if (isset($_POST[(string)$i])) {
            if ($_SESSION["kosar"][$i]->getNev()=="Egyedi hamburger" && isset($_SESSION["egyedi"])){
                unset($_SESSION["egyedi"]);
            }
            unset($_SESSION["kosar"][$i]);
            $_SESSION["kosar"]=array_values($_SESSION["kosar"]);
        }
    }
}

if (isset($_SESSION["kosar"])) {        //plusz
    for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {
        if (isset($_POST["P".$i])) {
            $_SESSION["kosar"][$i]->setMenyiseg($_SESSION["kosar"][$i]->getMenyiseg()+1);
        }
    }
}


if (isset($_SESSION["kosar"])) {        //minusz
    for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {
        if (isset($_POST["M".$i])) {
            $_SESSION["kosar"][$i]->setMenyiseg($_SESSION["kosar"][$i]->getMenyiseg()-1);
            if ($_SESSION["kosar"][$i]->getMenyiseg()==0){
                unset($_SESSION["kosar"][$i]);
                $_SESSION["kosar"]=array_values($_SESSION["kosar"]);
            }
        }
    }
}

if (isset($_SESSION["kosar"])) {        //mod
    for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {
        if (isset($_POST["Mo".$i])) {
            $_SESSION["egyedi"]=$_SESSION["kosar"][$i];
            unset($_SESSION["kosar"][$i]);
            $_SESSION["kosar"]=array_values($_SESSION["kosar"]);
            header("Location: hamburgermaker.php");
        }
    }
}

if (isset($_POST["rendel"]) && isset($_SESSION["kosar"])){
    $osszeg=0;
    $etel=[];
    for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {
        $hambi = $_SESSION["kosar"][$i];
        $osszeg += $hambi->ar();
        if($hambi->getKepNev()=="custom_hambi.png"){
            $oszetevok=$hambi->getAlapanyagok();
            $oszetevok["mennyiség"]=$hambi->getMenyiseg();
            $etel["egyedi"][]=$oszetevok;
        }else{
            $etel[]=$hambi->getNev()." X ".$hambi->getMenyiseg();
        }
    }

    $file=file_get_contents("json/rendelesek.json");
    $file=json_decode($file,true);
    $file[]=[
        "user"=>$_SESSION["user"]["username"],
        "név"=>$_SESSION["user"]["name"],
        "osszeg"=>$osszeg,
        "ételek"=>$etel
    ];
    $file=json_encode($file,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents("json/rendelesek.json",$file);
    unset($_SESSION["kosar"]);
    $uzenet='<p class="ureskosar">Sikeres rendelés!</p>';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/menu-kosar.css">
    <link rel="icon" href="img/icon_image.png"/>
    <title>Kosár</title>
</head>
<body>

<!--navigáció-->
 <nav>  
    <div class="dropdown">
        <button class="dropdown-button">
            <img src="img/navbar/dropdown.png" alt="dropdown" id="dropdown-icon">
        </button>
        <div class="dropdown-content">
            <a href="index.php">Kezdőlap</a>
            <a href="menu.php">Menü</a>
            <a href="hamburgermaker.php">Egyedi hamburger</a>
            <a href="asztalfoglalas.php">Asztalfoglalás</a>
        </div>
    </div>

    <div id="regiszter">
        <a href="kosar.php"><img src="img/navbar/kosar2.png" alt="kosar" class="icon"></a>
        <?php if (isset($_SESSION["user"])) {  ?>
            <a href="fiok.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } else { ?>
            <a href="login.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } ?>
    </div>

</nav>
<!--navigáció vége-->


<!--kosár tartalma-->
<main>
    <?php
    if (isset($_SESSION["kosar"]) && count($_SESSION["kosar"])!=0){
        $osszeg=0;
        for ($i = 0; $i < count($_SESSION["kosar"]); $i++) {
            $hambi=$_SESSION["kosar"][$i];
            if ($hambi==null){
                continue;
            }
            if ($i>2){
                $be="#".$i;
            }else{
                $be="";
            }
            $osszeg+=$hambi->ar();
            echo '<form action="kosar.php'.$be.'" id="'.$i.'" method="post">';
            echo '<div class="flex_box kosar-db">';
            if ($hambi->getKepNev()=="custom_hambi.png"){
                echo '<div class="img_container"><img src="img/hamburger/custom_hambi.png" alt="custom" class="egyedihambi_img"></div>';
                echo '<div class="flex_box">';
                echo '<div class="kosar-description">';
                echo '<p>' . $hambi->getNev() . '</p>';
                echo '<p>' . $hambi->ar() . 'FT</p>';
                echo '</div>';
                echo '<div class="opperation-buttons">';
                echo '<div class="mennyiseg_allitas">';
                echo '<label class="behuzas">Mennyiség:';
                echo '<input type="number" disabled   class="mennyiseg" min="1" max="10" value="'.$hambi->getMenyiseg().'">';
                echo '</label>';
                echo '<button class="plus-minus" name="P'.$i.'" type="submit">+</button>';
                echo '<button class="plus-minus" name="M'.$i.'" type="submit">-</button>';
                echo '</div>';
                echo '<div class="delete"><input type="submit" name="Mo'.$i.'" value="Módosít"><input type="submit" value="Törlés" name="'.$i.'"></div>';
            }else{
                echo '<div class="img_container"><img src="img/hamburger/'.$hambi->getKepNev() .'" alt="hambi"></div>';
                echo '<div class="flex_box">';
                echo '<div class="kosar-description">';
                echo '<p>' . $hambi->getNev() . '</p>';
                echo '<p>' . $hambi->ar() . 'FT</p>';
                echo '</div>';
                echo '<div class="opperation-buttons">';
                echo '<div class="mennyiseg_allitas">';
                echo '<label class="behuzas">Mennyiség:';
                echo '<input type="number" disabled  class="mennyiseg" min="1" max="10" value="'.$hambi->getMenyiseg().'">';
                echo '</label>';
                echo '<button class="plus-minus" name="P'.$i.'" type="submit">+</button>';
                echo '<button class="plus-minus" name="M'.$i.'" type="submit">-</button>';
                echo '</div>';
                echo '<div class="delete"><input type="submit" value="Törlés" name="'.$i.'"></div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</form>';

        }
        echo '<form action="kosar.php" method="post">';
        echo '<div id="megrendeles" class="flex_box">';
        echo '<p>Összesen: '.$osszeg.'Ft</p>';
        echo '<input type="submit" value="Rendel" name="rendel">';
        echo '</div>';
        echo '</form>';
    }else{
        echo $uzenet;
    }
    ?>


</main>
<!--kosár tartalmának vége-->


</body>
</html>