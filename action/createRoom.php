<?php 
require "../utils/common.php";

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
        $pdoStatement = $pdo->prepare("SELECT room_code FROM room WHERE room_code = :code");
        $pdoStatement->execute([
            ":code" => $code,
        ]);
        $verif_code = $pdoStatement->fetch();
    }
    $pdoStatement = $pdo->prepare("INSERT INTO room VALUE (:nom,:code,:visible,:nbr_max,0)");
    $pdoStatement->execute([
        ":nom" => $_POST["nom"],
        ":code" => $code,
        ":visible" => $_POST["visibilite"],
        ":nbr_max" => $_POST["nbr_joueur_max"],
    ]);
    $pdoStatement = $pdo->prepare("INSERT INTO partie VALUE (:code,:id_joueur,1,1)");
    $pdoStatement->execute([
        ":code" => $code,
        ":id_joueur" => $_SESSION["user_id"],
    ]);
    header("Location: ".PROJECT_FOLDER."jouer.php");
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