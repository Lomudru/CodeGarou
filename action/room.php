<?php
require "../utils/common.php";
require '../vendor/autoload.php';
require "../utils/functions.php";
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
    $room = Select_Room();
    sleep(1);
    foreach($room as $row){
        $verifRoom = Select_Partie($row->room_code);
        if(empty($verifRoom)){
            Delete_Room(false,$row->room_code);
            $data["room_code"] = $row->room_code;
            $data["delete"] = true;
            $pusher->trigger("Channel_room", 'room', $data);
        }
        $row->nbr_joueur = playeringame($row->room_code)->nbr_joueur;
    }
    echo json_encode($room);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}