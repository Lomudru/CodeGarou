<?php
require "../utils/common.php";

if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT J.joueur_pseudo FROM partie AS P INNER JOIN joueur AS J ON J.joueur_id = P.joueur_id  WHERE room_code = :code");
    $pdoStatement->execute([
        ":code" => $_GET["code"]
    ]);
    $result = $pdoStatement->fetchAll();
    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}