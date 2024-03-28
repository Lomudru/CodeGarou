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
    $room = $_GET["code"];
    if(isset($_SESSION["user_id"]) && isset($_GET["cible"]) && isset($_GET["tour"]) && $_GET["tour"]< 5 && isset($_GET["action"]) && $_GET["role"] && !isset($_GET["cibleTempo"])){
        $tour = $_GET["tour"];
        //regarder si la cible est definit
        if($_GET["cible"]=="undefined"){
            //inserer dans interaction l'action
            Insert_Interaction($room,$_GET["tour"],$_SESSION["user_id"],$_GET["action"],null);
        }
        else{
            //trouver l'id de la cible
            $cible = Select_Joueur(false,$_GET["cible"])[0];
            //inserer dans interaction l'action
            Insert_Interaction($room,$_GET["tour"],$_SESSION["user_id"],$_GET["action"],$cible->joueur_id);
        }
        $Prochain_role = Prochain_role($room);
        if ($Prochain_role == 1){
            $killLoup = faire_mourir($_GET["code"],"LOUP_KILL", $tour);
            if ($killLoup){
                Update_Partie(false,false,false,true,$_GET["code"],$killLoup->victime_id);
            }
            $tour++;
        }
        if(joueur_executant($room) == $_SESSION["user_id"]){
            var_dump("execute");
            sleep(1);
            Update_Room(false,false,false,false,false,false,$Prochain_role,false,$_GET["code"]);
            $data['ProchainRoleId'] = $Prochain_role;
            $data['tour'] = $tour;
            $data['killLoup'] = $killLoup;
            $pusher->trigger($_GET["code"], 'game', $data);
        }
    }elseif(isset($_SESSION["user_pseudo"]) && isset($_GET["cible"]) && isset($_GET["cibleTempo"])){
        $data['user_pseudo'] = $_SESSION["user_pseudo"];
        $data['cible'] = $_GET["cible"];
        $pusher->trigger($_GET["code"], 'lgVote', $data);
    }elseif(isset($_SESSION["user_id"])){
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("SELECT R.role_nom FROM partie AS P INNER JOIN role AS R ON P.role_id = R.role_id WHERE room_code = :code AND joueur_id = :id");
        $pdoStatement->execute([
            ":code" => $_GET["code"],
            ":id" => $_SESSION["user_id"]
        ]);
        $result = $pdoStatement->fetch();
        echo json_encode($result);
    }
}