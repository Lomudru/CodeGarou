<?php
require "../utils/common.php";

if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"]) && !isset($_GET["post"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT C.room_code, C.chat_msg, J.joueur_pseudo FROM chat AS C INNER JOIN joueur AS J ON J.joueur_id = C.joueur_id WHERE C.room_code = :code ORDER BY chat_date ASC");
    $pdoStatement->execute([
        ":code" => $_GET["code"]
    ]);
    $result = $pdoStatement->fetchAll();
    echo json_encode($result);
}elseif(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"]) && isset($_GET["post"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO chat(room_code, joueur_id, chat_msg) VALUES (:code, :id, :msg)");
    $pdoStatement->execute([
        ":code" => $_GET["code"],
        ":id" => $_SESSION["user_id"],
        ":msg" => $_GET["post"]
    ]);
}
else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}