<?php
session_start();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="icon" href="img/icon_image.png"/>
    <title>Főoldal</title>
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


<main>
<!--Logo-->
    <div id="#cim">
        <h1>Big P's Hamburgers</h1>
    </div>
<!--Logo vége-->


<!--Bevezető-->
    <div id="bevezetocontainer">
        <!--Bevezető szöveg-->
        <div class="textbox" id="bevezeto-szoveg">
            <h2>Bevezető</h2>
            <hr>
            <p>Üdvözöljük a Big P's Hamburgers hivatalos oldalán! Péter, az alapító, szenvedélyesen hisz abban, hogy minden falat egy különleges élmény. Fedezze fel változatos, ínycsiklandó hamburgereinket, rendeljen kényelmesen online vagy foglaljon asztalt, hogy személyesen élvezhesse a jókedvet, amelyet minden egyes harapás garantál. Legyen részese a Big P's hangulatának, és kóstolja meg a világ legjobb hamburgereit, itt, Szegeden!</p>
            </div>
        <!--Bevezető szöveg vége-->

        <!--Slideshow-->
        <div class="slideshow">
                 <div class="slideshow-container">
                    <div class="mySlides fade">
                        <div class="numbertext">1 / 4</div>
                        <img src="img/hamburger/hazajanlat.jpg" alt="hamburger" class="slideshow-img">
                        <div class="slideShow-text">Big P speciális hamburgere</div>
                    </div>
                
                    <div class="mySlides fade">
                        <div class="numbertext">2 / 4</div>
                        <img src="img/étterem3.jpg" alt="Étterem" class="slideshow-img">
                        <div class="slideShow-text">Étkező</div>
                    </div>
                
                    <div class="mySlides fade">
                      <div class="numbertext">3 / 4</div>
                      <img src="img/Big_P.jpg" alt="Big P" class="slideshow-img">
                      <div class="slideShow-text">Big P</div>
                    </div>
                
                    <div class="mySlides fade">
                      <div class="numbertext">4 / 4</div>
                      <img src="img/kormanyzo.jpg" alt="Korrupt" class="slideshow-img">
                      <div class="slideShow-text">A korrupt üzletember</div>
                    </div>
                
                    <a class="prev" onclick="plusSlides(-1)">&lt;</a>
                    <a class="next" onclick="plusSlides(1)">&gt;</a>
                </div>
            </div>
        <!--Slideshow vége-->
    </div>
<!--Bevezető vége-->

<script src="java/slideshow.js"></script>

<!--Történet-->
<div class="textbox">
    <h2>Történet</h2>
    <hr>
    <p>Egyszer volt, hol nem volt, a kisvárosunkban nyílt egy új hamburgerező, a Big P's Hamburgers. A tulajdonos, Péter, egy igazi karakter volt, aki mindig mosolygott és imádta a hamburgereket. A hamburgerek iránti szeretetét az egész világgal meg akarta osztani, de sajnos egyedül erre nem volt képes. Viszont egy csodás napon találkozott Márkkal, aki egy sikeres (és enyhén korrupt) üzletember. Márk, habár a pizzát jobban szereti mint a hamburgert, a pénzt szereti a legjobban, így mikor meglátta Péter hamburgereiben a lehetőséget rögtön egy ajánlattal fordult felé: alakítsanak közösen egy hamburgerezőt. És így nyílt meg a világ legjobb hamburgerezője a Big P's.</p>
</div>
<!--Történet vége-->

<!--Elérhetőség-->
<h2>Elérhetőség</h2>
    <div id="elerhetosegcontainer">
        <div id="elerhetoseg-felsorolas">
            <ul>
                <li>Szeged, Thököly u. 53</li>
                <li>bigpshamburgers@gmail.com</li>
                <li>+36 20 314 2537</li>
            </ul>
            <div id="facebook">
                <a href=https://www.facebook.com/groups/335059089528945" target="_blank"><img src="img/facelogo.png" alt="facebook" id="facelogo" title="Kövess be minket facebookon!"></a>
                <p>Kövess be minket facebookon!</p>
            </div>
        </div>
    </div>
<!--Elérhetőség vége-->

</main>
</body>
</html>
