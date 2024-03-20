<?php
require "../utils/common.php";

if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("UPDATE `room` SET `room_en_jeu`= 1 WHERE room_code = :code");
    $pdoStatement->execute([
        ":code" => $_GET["code"]
    ]);
    $pdoStatement = $pdo->prepare("SELECT * FROM partie WHERE room_code = :code");
    $pdoStatement->execute([
        ":code" => $_GET["code"]
    ]);
    $result = $pdoStatement->fetchAll();

    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}