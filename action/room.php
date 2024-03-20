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
if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483"){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM room");
    $pdoStatement->execute([]);
    $result = $pdoStatement->fetchAll();
    sleep(1);
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
            $data["room_code"] = $row->room_code;
            $data["delete"] = true;
            $pusher->trigger("Channel_room", 'room', $data);
        }
    }
    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}