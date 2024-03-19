<?php
require "../utils/common.php";

if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483"){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM room WHERE room_visibilite = 1 AND room_en_jeu = 0");
    $pdoStatement->execute([]);
    $result = $pdoStatement->fetchAll();
    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}