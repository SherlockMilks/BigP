<?php
include "json_control.php";
session_start();

$folglalasok=$_SESSION["user"]["foglalasok"];
foreach ($folglalasok as $foglalas){
    asztal_torles("../json/asztalok.json",$_SESSION["user"]["username"],$foglalas);
}
delete_user("../json/user.json",$_SESSION["user"]["username"],"../");
header("Location: logout.php");
?>
