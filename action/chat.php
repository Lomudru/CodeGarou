<?php
require "../utils/common.php";
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


if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"]) && !isset($_GET["post"])){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT C.room_code, C.chat_msg, IFNULL(J.joueur_pseudo, 'SYSTEME') AS joueur_pseudo FROM chat AS C LEFT JOIN joueur AS J ON J.joueur_id = C.joueur_id WHERE C.room_code = :code ORDER BY C.chat_date ASC;");
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
    $data['message'] = $_GET["post"];
    $data['user_pseudo'] = $_SESSION["user_pseudo"];
    $pusher->trigger($_GET["code"], 'chat', $data);
}
else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}