<?php 
require "../utils/common.php";
require "../utils/functions.php";
require '../vendor/autoload.php';
$options = array(
    'cluster' => 'eu',
    'useTLS' => true
);
$pusher = new Pusher\Pusher(
    'e39c485e91fd48c1c3e7',
    '2dfef9c32cb8dd9d44cb',
    '1774389',
    $options
);
if (!empty($_POST)){
    if ($_POST["nom"] == "" &&  strlen($_POST["nom"]) > 50){
        header("Location: ".PROJECT_FOLDER."jouer.php?error=name");
    }
    if ($_POST["visibilite"] != "1" && $_POST["visibilite"] != "0"){
        header("Location: ".PROJECT_FOLDER."jouer.php?error=visibilite");
    }
    if ($_POST["nbr_joueur_max"] < 4 || $_POST["nbr_joueur_max"] > 30){
        header("Location: ".PROJECT_FOLDER."jouer.php?error=nbr_max");
    }
    $pdo = connectToDbAndGetPdo();
    $verif_code = true;
    while($verif_code != false){
        $code = code_generator(5);
        $verif_code = Select_Room("room_code = $code");
    }

    $data["room_code"] = $code;
    $data["room_nom"] = $_POST["nom"];
    $data["nbr_max"] = $_POST["nbr_joueur_max"];
    $data["visibility"] = $_POST["visibilite"];

    $pusher->trigger("Channel_room", 'room', $data);

    Insert_Room($_POST["nom"],$code,$_POST["visibilite"],$_POST["nbr_joueur_max"],0);
    
    header("Location: ".PROJECT_FOLDER."jeu.php?code=". $code);
}else{
    header("Location: ".PROJECT_FOLDER."jouer.php");
}

function code_generator($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
 
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}
?>