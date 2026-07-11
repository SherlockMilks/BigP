<?php
session_start();
include "function/json_control.php";

if (!isset($_SESSION["user"])){
    header("Location: index.php");
}

$fiokok = load_users("json/user.json")["users"];

$hibak = [];
if (isset($_POST["mod"])) {

    if (!isset($_POST["username"]) || trim($_POST["username"]) === "")
        $hibak[] = "A felhasználónév megadása kötelező!";

    if (!isset($_POST["old_password"]) || trim($_POST["old_password"]) ===""){
        $hibak[] = "A régi jelszó megadása kötelező!";
    }

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

    if(isset($_SESSION["user"]["foglalasok"]) && count($_SESSION["user"]["foglalasok"])>0 && $_POST["username"]!=$_SESSION["user"]["username"]){
        $hibak[] = "Nem változtathat felhasználónevet ha van foglalása!";
    }


    $username = $_POST["username"];
    $old_pass=$_SESSION["user"]["password"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $birthdate = $_POST["birthdate"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $hirlevel=isset($_POST["level"]);
    $foglalasok=$_SESSION["user"]["foglalasok"];


    for ($i = 0; $i < count($fiokok); $i++) {
        if($fiokok[$i]["username"]===$username && $fiokok[$i]["username"]!=$_SESSION["user"]["username"]){
            $hibak[]="A felhasználónév már foglalt!";
        }
        if($fiokok[$i]["email"]===$email && $fiokok[$i]["email"]!=$_SESSION["user"]["email"]){
            $hibak[]="Az email már foglalt!";
        }
    }

    if (isset($_POST["old_password"]) && !password_verify($_POST["old_password"],$old_pass)){
        $hibak[] = "Nem ez a régi jelszava!";
    }

    if ($password!=null && $password2!=null){

        if (strlen($password) < 8){
            $hibak[] = "A jelszónak legalább 8 karakter hosszúnak kell lennie!";
        }

        if ($password !== $password2){
            $hibak[] = "A jelszó és az ellenőrző jelszó nem egyezik!";
        }

    }

    if ($birthdate > date("Y-m-d")){
        $hibak[]="A jövőben nem születhetett!";
    }


    if (count($hibak) === 0) {
        delete_user("json/user.json",$_SESSION["user"]["username"],"");

        if(isset($_POST["level"])){
            $json = file_get_contents("json/hirlevelcimek.json");
            $json = json_decode($json,true);
            $json[] = $email;
            $json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents("json/hirlevelcimek.json",$json);
        }

        if ($password!=null){
            $password = password_hash($password, PASSWORD_DEFAULT);
        }else{
            $password=$old_pass;
        }

        $uj = [
            "username" => $username,
            "password" => $password,
            "birthdate" => $birthdate,
            "name" => $name,
            "email" => $email,
            "hirlevel" => $hirlevel,
            "foglalasok"=>$foglalasok

        ];

        $_SESSION["user"]=$uj;
        save_users("json/user.json", $uj);
        header("Location: fiok.php");
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
    <title>Adat módosítás</title>
</head>
<body>

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
        <?php if (isset($_SESSION["user"])) {  ?>
            <a href="fiok.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } else { ?>
            <a href="login.php"><img src="img/navbar/login.png" alt="login" class="icon"></a>
        <?php } ?>
    </div>
</nav>
<!--Navigáció vége-->

<!--Regisztráció-->
<div class="login-container-register">
    <h2>Adat módosítás</h2>
    <form action="modosit.php" method="post">
        <fieldset>
            <legend><b>Személyes adatok</b></legend>
            <label>
                <b>Teljes név</b>
                <input type="text" <?php echo 'value="'.$_SESSION["user"]["name"].'"'?> name="name" placeholder="Gyúrós Feri"  required>
            </label>
            <label>
                <b>Email</b>
                <input type="email" <?php echo 'value="'.$_SESSION["user"]["email"].'"'?> name="email" placeholder="ferikeakiraly@gmail.com"  required>
            </label>
            <label>
                <b>Születési dátum</b>
                <input type="date" <?php echo 'value="'.$_SESSION["user"]["birthdate"].'"'?> name="birthdate" required>
            </label>
        </fieldset>
        <br>

        <label>
            <b>Felhasználónév</b> (csak akkor válthat ha nem foglalt asztalt)
            <input type="text" name="username" <?php echo 'value="'.$_SESSION["user"]["username"].'"'?> maxlength="15" required>
        </label>
        <label> <b>Régi jelszó</b>
            <input type="password" name="old_password" placeholder="*********" maxlength="20" required>
        </label>
        <label> <b>Új jelszó</b> (min 8 karakter)
            <input type="password" name="password" placeholder="*********" maxlength="20">
        </label>
        <label> <b>Új jelszó megerősítés</b>
            <input type="password" name="password2" placeholder="*********" maxlength="20">
        </label>
        <label><b>Feliratkozik a hírlevelünkre?</b>
            <input type="checkbox" <?php if($_SESSION["user"]["hirlevel"]){ echo "checked"; }?> name="level">
        </label>

        <input type="submit"  name="mod" value="Mentés">
        <?php
        foreach ($hibak as $uz){
            echo "<p class='hiba'>".$uz."</p>";
            echo "</br>";
        }
        ?>
    </form>
</div>

<!--Regisztráció vége-->

</body>
</html>
