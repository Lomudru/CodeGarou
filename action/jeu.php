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
if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"])){
    $result = Select_Partie($_GET["code"]);
    $alreadyLG = [];
    $villagois = [];
    foreach($result as $row){
        array_push($villagois ,$row);
    }
    for ($i=0; $i < intval(round(sizeof($result)/1,  $precision = 0)); $i++){
        $id_loup_garou = $result[random_int(0,sizeof($result)-1)];
        if(!in_array($id_loup_garou, $alreadyLG)){
            Update_Partie(false,false,2,false,false,$id_loup_garou->joueur_id);
            array_push($alreadyLG, $id_loup_garou);
            unset($villagois[array_search($id_loup_garou, $villagois)]);
        }else{
            $i--;
        }
    }
    $prochain_role = Prochain_role($_GET["code"]);
    Update_Room(false,false,false,false,1,false,$prochain_role,false,$_GET["code"]);

    $data["room_code"] = $_GET["code"];
    $data["delete"] = true;
    $data["Loup-Garou"] = $alreadyLG;
    $data["Village"] =  $villagois;
    $pusher->trigger($_GET["code"], 'launch', $data);
    echo json_encode($result);
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}