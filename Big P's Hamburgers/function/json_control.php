<?php
function save_users(string $path, array $data) {
    $users = load_users($path);

    $users["users"][] = $data;

    $json_data = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    file_put_contents($path, $json_data);
}

function load_users(string $path): array {
    if (!file_exists($path))
        die("Nem sikerült a fájl megnyitása!");

    $json = file_get_contents($path);

    return json_decode($json, true);
}

function delete_user($path,$username,$ki){
    $users2=load_users($path);
    $users=$users2["users"];
    $email=file_get_contents($ki."json/hirlevelcimek.json");
    $email=json_decode($email,true);

    for ($i = 0; $i < count($users); $i++) {        //adatok torlese
        if ($users[$i]["username"]===$username){
            $email2=$users[$i]["email"];
            unset($users[$i]);
            $users=array_values($users);
        }
    }
    $users2["users"]=$users;


    for ($i = 0; $i < count($email); $i++) {    //email torles
        if ($email[$i] == $email2) {
                unset($email[$i]);
                $email = array_values($email);
            }
        }


    $json_data2 = json_encode($email, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    file_put_contents($ki."json/hirlevelcimek.json", $json_data2);

    $json_data = json_encode($users2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    file_put_contents($path, $json_data);
}

function asztal_betolt ($path){
    $json=file_get_contents($path);
    return json_decode($json,true)["asztalok"];
}

function asztal_torles($path,$username,$time){
    $asztalok=asztal_betolt($path);
    for ($i = 0; $i < count($asztalok); $i++) {
        $foglalas=$asztalok[$i]["foglalasok"];
        if ($foglalas!=null){
            foreach ($foglalas as $ido => $user){
                if ($ido==$time && $user==$username){
                    unset($asztalok[$i]["foglalasok"][$ido]);
                }
            }
        }
    }

    $uj["asztalok"]=$asztalok;


    $json_data = json_encode($uj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($path,$json_data);

}


?>