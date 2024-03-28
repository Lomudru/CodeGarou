<?php
require "../utils/common.php";
require "../utils/functions.php";


if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"]) && isset($_SESSION["user_id"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM partie AS P INNER JOIN role AS R ON P.role_id = R.role_id WHERE room_code = :code AND joueur_id = :id");
    $pdoStatement->execute([
        ":code" => $_GET["code"],
        ":id" => $_SESSION["user_id"]
    ]);
    $result = $pdoStatement->fetchAll();
    echo json_encode($result);
}