<?php
session_start();
include "function/json_control.php";
$fiokok=load_users("json/user.json")["users"];
$uzenet="";

if(isset($_POST["login"])) {
    if (!isset($_POST["username"]) || trim($_POST["username"]) === "" || !isset($_POST["password"]) || trim($_POST["password"]) === "") {
        $uzenet = "<strong>Hiba:</strong> Adj meg minden adatot!";
        $siker=false;
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
    }

    for ($i = 0; $i < count($fiokok); $i++) {
        if($fiokok[$i]["username"]===$username && password_verify($password, $fiokok[$i]["password"])){
             $uzenet = "Sikeres belépés!";
             $_SESSION["user"]=$fiokok[$i];
             header("Location: index.php");
        }else{
             $uzenet="Sikertelen belépés! A belépési adatok nem megfelelők!";
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/login.css">
    <link rel="icon" href="img/icon_image.png"/>
    <title>Bejelentkezés</title>
</head>
<body>
<script src="java/login.js"></script>

<!--Navigáció-->
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
        <a href="kosar.php"><img src="img/navbar/kosar.png" alt="kosar" class="icon"></a>
        <a href="login.php"><img src="img/navbar/login-current.png" alt="login" class="icon"></a>
    </div>
</nav>
<!--Navigáció vége-->

<!--Bejelentkezés-->
<div class="login-container">
    <h2>Bejelentkezés</h2>
    <form action="login.php" method="post">
        <label>
            <b>Felhasználónév</b>
            <input type="text" name="username" placeholder="ferike86" maxlength="15" required>
        </label>
        <label> <b>Jelszó</b>
            <input type="password" name="password" id="password" placeholder="*********" maxlength="20" required>
            <span class="toggle-password" onclick="jelszoMegjelenites()">
                <img src="img/szemecske.png" alt="jelszoMegjelenites">
            </span>
        </label>

        <input type="submit" name="login" value="Bejelentkezek!">
    </form>

    <a href="register.php" id="gomb">Regisztráció</a>
    <?php
        echo "<p class='hiba'>" . $uzenet . "</p>";
        echo "</br>";
    ?>
</div>
<!--Bejelentkezés vége-->

</body>
</html>