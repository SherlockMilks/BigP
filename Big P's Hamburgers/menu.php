<?php
include "hambi.php";
session_start();

if(!isset($_SESSION["siker"])){
    $_SESSION["siker"]=0;
}


function bovit($hambi){
    foreach ($_SESSION["kosar"] as $kosar){            //ilyen hamburger van-e már a kosárban
        if ($kosar->compare($hambi)){
            if($kosar->getMenyiseg()+1>10){            //van-e már 10 a kosárban
                $_SESSION["siker"]=2;
                return true;
            }
            $kosar->setMenyiseg($kosar->getMenyiseg()+1);
            $_SESSION["siker"]=1;
            return true;
        }
    }
    return false;
}

function berak($hambi){
    if (isset($_SESSION["kosar"])){
        if (!bovit($hambi)){
            $_SESSION["kosar"][]=$hambi;
            $_SESSION["siker"]=1;
        }
    }
    else{
        $_SESSION["kosar"][]=$hambi;
        $_SESSION["siker"]=1;
    }

}


if (isset($_POST["alap"])){
    $hambi = new hambi("Alap hamburger","sima.jpg",["Buci","Marhahús", "Jégsaláta", "Paradicsom", "Uborka", "Ketchup", "Mustár"]);
    berak($hambi);
    header("Location: menu.php");
}

if (isset($_POST["dupla"])){
    $hambi = new hambi("Dupla burger", "duplahus.jpg", ["Marhahús","Marhahús", "Jégsaláta", "Uborka", "Ketchup", "Mustár", "Buci"]);
    berak($hambi);
    header("Location: menu.php");
}

if (isset($_POST["bacon"])){
    $hambi = new hambi("Bacon burger", "bacon.jpg", ["Buci", "Marhahús", "Bacon", "Jégsaláta", "Uborka", "Paradicsom", "Ketchup", "Mustár"]);
    berak($hambi);
    header("Location: menu.php");
}

if (isset($_POST["csirke"])){
    $hambi = new hambi("Csirke burger", "csirkes.jpg", ["Csirkehús", "Jégsaláta", "Uborka", "Paradicsom", "Majonéz", "Buci"]);;
    berak($hambi);
    header("Location: menu.php#csirke");
}

if (isset($_POST["csirkemarha"])){
    $hambi = new hambi("Marha+csirke burger", "csirke+marha.webp", ["Marhahús", "Csirkehús", "Jégsaláta", "Paradicsom", "Majonéz", "Buci"]);
    berak($hambi);
    header("Location: menu.php#csirke");
}

if (isset($_POST["hagymas"])){
    $hambi = new hambi("Hagymaimádó burger", "hagymas.png", ["Marhahús", "Pirított hagyma", "Lila hagyma", "Jégsaláta", "BBQ szósz", "Vörös hagyma",  "Hagymalekvár", "Buci"]);;
    berak($hambi);
    header("Location: menu.php#csirke");
}

if (isset($_POST["duplabacon"])){
    $hambi = new hambi("Bacon mánia burger", "duplabacon.jpg", ["Buci", "Marhahús", "Bacon", "Bacon", "Jégsaláta", "Mustár", "Ketchup", "Pirított hagyma"]);;
    berak($hambi);
    header("Location: menu.php#bacon_m");
}

if (isset($_POST["hazajanlata"])){
    $hambi = new hambi("Ház ajánlata", "hazajanlat.jpg", ["Marhahús","Marhahús","Bacon", "Jégsaláta", "Uborka", "Lila hagyma", "Hamburgerszósz", "Buci"]);
    berak($hambi);
    header("Location: menu.php#bacon_m");
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
    <title>Menü</title>
</head>
<body>
<!--Navigácio-->
<nav>
    <div class="dropdown">
        <button class="dropdown-button">
            <img src="img/navbar/dropdown.png" alt="dropdown" id="dropdown-icon">
        </button>
        <div class="dropdown-content">
            <a href="index.php">Kezdőlap</a>
            <a href="menu.php" id="current">Menü</a>
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
<!--Navigácio vége-->

<!--Hamburger lista-->
<main>
    <h2 id="menu-cim">Menü</h2>
    <?php
    if ($_SESSION["siker"]==1){
        echo "<p class='sikeres'>Sikeresen hozzáadva a kosárhoz! (max 10db/típus)</p>";
    }
    if ($_SESSION["siker"]==2){
        echo "<p class='hiba'>Ebből a hamburgerből már nem fér több a kosárba!</p>";
    }
    ?>
    <div class="menu">


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Alap hamburger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/sima.jpg" alt="alap"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>marhahús</li>
                        <li>jégsaláta</li>
                        <li>paradicsom</li>
                        <li>uborka</li>
                        <li>ketchup</li>
                        <li>mustár</li>
                    </ul>
                </div>
                <div class="order">
                    <p>1800Ft</p>
                    <?php
                        if (isset($_SESSION["user"])){
                            echo '<input type="submit" value="Kosárba!" name="alap">';
                        }else{
                            echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }

                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Dupla burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/duplahus.jpg" alt="dupla"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>dupla marhahús</li>
                        <li>jégsaláta</li>
                        <li>uborka</li>
                        <li>ketchup</li>
                        <li>mustár</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2300Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="dupla">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Bacon burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/bacon.jpg" alt="bacon"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>marhahús</li>
                        <li>bacon</li>
                        <li>jégsaláta</li>
                        <li>uborka</li>
                        <li>paradicsom</li>
                        <li>ketchup</li>
                        <li>mustár</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2150Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="bacon">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" id="csirke" method="post">
            <h3>Csirke burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/csirkes.jpg" alt="csirke"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>ropogós csirke</li>
                        <li>jégsaláta</li>
                        <li>uborka</li>
                        <li>paradicsom</li>
                        <li>majonéz</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">1775Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="csirke">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Marha+csirke burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/csirke+marha.webp" alt="csirkemarha"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>marhahús</li>
                        <li>ropogós csirke</li>
                        <li>jégsaláta</li>
                        <li>paradicsom</li>
                        <li>majonéz</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2275Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="csirkemarha">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Hagymaimádó burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/hagymas.png" alt="hagymas"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>marhahús</li>
                        <li>jégsaláta</li>
                        <li>pirított hagyma</li>
                        <li>hagyma</li>
                        <li>hagymalekvár</li>
                        <li>BBQ szósz</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2150Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="hagymas">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" id="bacon_m" method="post">
            <h3>Bacon mánia burger</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/duplabacon.jpg" alt="duplabacon"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>marhahús</li>
                        <li>dupla bacon</li>
                        <li>jégsaláta</li>
                        <li>pirított hagyma</li>
                        <li>ketchup</li>
                        <li>mustár</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2400Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="duplabacon">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>


        <div class="hambi">
            <form action="menu.php" method="post">
            <h3>Ház ajánlata</h3>
            <div class="flex_box">
                <div><img src="img/hamburger/hazajanlat.jpg" alt="hazajanlata"></div>
                <div class="hambi-description">
                    <p>Alapanyagok: </p>
                    <ul>
                        <li>dupla marhahús</li>
                        <li>bacon</li>
                        <li>jégsaláta</li>
                        <li>uborka</li>
                        <li>hagyma</li>
                        <li>házi szósz</li>
                    </ul>
                </div>
                <div class="order">
                    <p class="ar">2800Ft</p>
                    <?php
                    if (isset($_SESSION["user"])){
                        echo '<input type="submit" value="Kosárba!" name="hazajanlata">';
                        }else{
                        echo "<p>A rendeléshez be kell jelentkezni!</p>";
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>

    </div>

</main>
<!--Hamburger lista vége-->




</body>
</html>