<?php
require "../utils/common.php";


if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483"){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM room");
    $pdoStatement->execute([]);
    $result = $pdoStatement->fetchAll();
    foreach($result as $row){
        $pdoStatement = $pdo->prepare("SELECT * FROM partie WHERE room_code = :code");
        $pdoStatement->execute([
            ":code" => $row->room_code
        ]);
        $verifRoom = $pdoStatement->fetch();
        if($verifRoom == false){
            $pdoStatement = $pdo->prepare("DELETE FROM `room` WHERE room_code = :code");
            $pdoStatement->execute([
                ":code" => $row->room_code
            ]);
            $verifRoom = $pdoStatement->fetch();
        }
    }
    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}