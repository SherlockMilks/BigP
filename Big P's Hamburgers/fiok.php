<?php
session_start();
include "function/json_control.php";

if(!isset($_SESSION["user"])){
    header("Location: login.php");
}

$userInfo = array(
'name' => $_SESSION["user"]["name"],
'email' => $_SESSION["user"]["email"],
'birthdate' => $_SESSION["user"]["birthdate"],
'username' => $_SESSION["user"]["username"],
'bookedtables' => $_SESSION["user"]["foglalasok"],
'hirlevel' => $_SESSION["user"]["hirlevel"]
);

for ($i=0;$i<count($_SESSION["user"]["foglalasok"]);$i++){
    if (isset($_POST[(string)$i])){
        asztal_torles("json/asztalok.json",$_SESSION["user"]["username"],$_SESSION["user"]["foglalasok"][$i]);
        unset($_SESSION["user"]["foglalasok"][$i]);
        $_SESSION["user"]["foglalasok"]=array_values($_SESSION["user"]["foglalasok"]);

        $users2=load_users("json/user.json");
        $users=$users2["users"];

        for ($j = 0; $j < count($users); $j++) {        //foglalasok torlese
            if ($users[$j]["username"]===$_SESSION["user"]["username"]){
                unset($users[$j]["foglalasok"][$i]);
                $users[$j]["foglalasok"]=array_values($users[$j]["foglalasok"]);
                $users2["users"]=$users;
                $json_data = json_encode($users2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents("json/user.json", $json_data);
                break;
            }
        }
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
    <title>Profil</title>
</head>
<body>

<!-- Navigáció kezdete -->
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
<!-- Navigáció vége -->

<!-- Profil adatok -->
<div class="profile-container">
    <h2><?php echo $userInfo['username']; ?></h2>
    <div class="flex_box">
        <div class="profile-info">
            <p><b>Név:</b> <?php echo $userInfo['name']; ?></p>
            <p><b>Email:</b> <?php echo $userInfo['email']; ?></p>
            <p><b>Születési dátum:</b> <?php echo $userInfo['birthdate']; ?></p>
            <p><b>Feliratkozott a hírlevelünkre?</b> <?php echo $userInfo['hirlevel'] ? "Igen :)" : "Nem :(" ?></p>
        </div>
        <div class="foglalasok">
            <h1>Foglalások:</h1>
            <form action="fiok.php" method="post">
            <?php
            if(!($userInfo['bookedtables']==null)){
                $db=0;
                foreach ($userInfo['bookedtables'] as $idopont) {
                    $darabolt = explode('-', $idopont);
                    $datum = $darabolt[0].'. '.$darabolt[1].'. '.$darabolt[2].'.';
                    $ora = $darabolt[3].'h';
                    ?>
                    <p><?php echo $datum . ' ' . $ora; ?><input type="submit" value="X" name=<?php echo '"'.$db.'"'; $db++;?>></p>
                <?php }} ?>
            </form>
        </div>
    </div>
    <a href="function/logout.php" class="profile_button">Kijelentkezés</a>
    <a href="modosit.php" class="profile_button">Módosítás</a>
    <a href="function/acc_delete.php" class="profile_button">Fiók törlése</a>
</div>
<!-- Profil adatok vége-->



</body>
</html>
