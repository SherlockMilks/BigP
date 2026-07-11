<?php
include "function/json_control.php";

$fiokok = load_users("json/user.json")["users"];

$hibak = [];
if (isset($_POST["reg"])) {
    if (!isset($_POST["username"]) || trim($_POST["username"]) === "")
        $hibak[] = "A felhasználónév megadása kötelező!";

    if (!isset($_POST["password"]) || trim($_POST["password"]) === "" || !isset($_POST["password2"]) || trim($_POST["password2"]) === "")
        $hibak[] = "A jelszó és az ellenőrző jelszó megadása kötelező!";

    if (!isset($_POST["birthdate"]) || trim($_POST["birthdate"]) === "")
        $hibak[] = "A születési dátum megadása kötelező!";

    if(count(explode(" ",trim($_POST["name"])))<2)
        $hibak[] = "A teljes név megadása kötelező!";

    if (!isset($_POST["email"]) || trim($_POST["email"]) === "")
        $hibak[] = "Az email megadása kötelező!";

    if(!preg_match('/^[a-z0-9.-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/',$_POST["email"]))
        $hibak[] = "Az email formátuma nem megfelelő!";

    if(!preg_match('/^[0-9]{4}.(0[1-9]|1[0-2]).(0[1-9]|[12][0-9]|3[01]).?$/',$_POST["birthdate"]))
        $hibak[] = "A születési dátum formátuma nem megfelelő!";



    $username = $_POST["username"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $birthdate = $_POST["birthdate"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $hirlevel=isset($_POST["level"]);

    for ($i = 0; $i < count($fiokok); $i++) {
        if($fiokok[$i]["username"]===$username){
            $hibak[]="A felhasználónév már foglalt!";
        }
        if($fiokok[$i]["email"]===$email){
            $hibak[]="Az email már foglalt!";
        }
    }

    if (strlen($password) < 8)
        $hibak[] = "A jelszónak legalább 8 karakter hosszúnak kell lennie!";

    if ($password !== $password2)
        $hibak[] = "A jelszó és az ellenőrző jelszó nem egyezik!";

    if ($birthdate > date("Y-m-d")){
        $hibak[]="A jövőben nem születhetett!";
    }


    if (count($hibak) === 0) {
        if(isset($_POST["level"])){
            $json = file_get_contents("json/hirlevelcimek.json");
            $json = json_decode($json,true);
            $json[] = $email;
            $json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents("json/hirlevelcimek.json",$json);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $uj = [
            "username" => $username,
            "password" => $password,
            "birthdate" => $birthdate,
            "name" => $name,
            "email" => $email,
            "hirlevel" => $hirlevel,
            "foglalasok"=>[]

        ];
        save_users("json/user.json", $uj);
        header("Location: login.php");
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
    <title>Regisztráció</title>
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

<!--Regisztráció-->
<div class="login-container-register">
    <h2>Regisztráció</h2>
    <form action="register.php" method="post">
        <fieldset>
            <legend><b>Személyes adatok</b></legend>
            <label>
                <b>Teljes név</b>
                <input type="text" name="name" placeholder="Gyúrós Feri"  required>
            </label>
            <label>
                <b>Email</b>
                <input type="email" name="email" placeholder="ferikeakiraly@gmail.com"  required>
            </label>
            <label>
                <b>Születési dátum</b>
                <input type="date" name="birthdate" required>
            </label>
        </fieldset>
        <br>

        <label>
            <b>Felhasználónév</b>
            <input type="text" name="username" placeholder="ferike86" maxlength="15" required>
        </label>
        <label> <b>Jelszó</b> (min 8 karakter)
            <input type="password" name="password" id="password" placeholder="*********" maxlength="20" required>
            <span class="toggle-password" onclick="jelszoMegjelenites()">
                <img src="img/szemecske.png" alt="jelszoMegjelenites">
            </span>
        </label>
        <label> <b>Jelszó megerősítés</b>
            <input type="password" name="password2"  placeholder="*********" maxlength="20" required>
        </label>
        <label><b>Feliratkozik a hírlevelünkre?</b>
            <input type="checkbox" name="level">
        </label>

        <input type="submit" name="reg" value="Regisztrálok!">
    </form>
    <a href="login.php" id="gomb">Bejelentkezés</a>
    <?php
    foreach ($hibak as $uz){
        echo "<p class='hiba'>".$uz."</p>";
        echo "</br>";
    }
    ?>
</div>

<!--Regisztráció vége-->

</body>
</html>