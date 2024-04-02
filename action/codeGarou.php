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

if(isset($_GET["key"]) && $_GET["key"] == "cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483" && isset($_GET["code"]) && isset($_SESSION["user_id"]) && isset($_GET["cible"]) && $_GET["role"]){
    $room = $_GET["code"];
    if(isset($_GET["tour"]) && $_GET["tour"]< 5 && isset($_GET["action"]) && !isset($_GET["cibleTempo"])){
        $tour = $_GET["tour"];
        $mort = [];
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
        if(joueur_executant($room) == $_SESSION["user_id"]){
            if ($Prochain_role == 1){
                $killLoup = faire_mourir($room,"LOUP_KILL", $tour);
                if ($killLoup){
                    Update_Partie(false,false,false,true,$room,$killLoup->victime_id);
                    array_push($mort,$killLoup);
                }
                $tour++;
            }elseif($_GET["role"]==1){
                $vote = faire_mourir($room,"VOTE", $tour);
                if ($vote){
                    Update_Partie(false,false,false,true,$room,$vote->victime_id);
                    array_push($mort,$vote);
                }
            }
            Update_Room(false,false,false,false,false,false,$Prochain_role,false,$room);
            $data['ProchainRoleId'] = $Prochain_role;
            $victoireValue = victoire($room);
            if($victoireValue == false){
                $data['tour'] = $tour;
            }else{
                $data['tour'] = $victoireValue->role_camp;
            }
            $data['mort'] = $mort;
            $pusher->trigger($room, 'game', $data);
        }
    }elseif(isset($_SESSION["user_pseudo"])&& isset($_GET["cibleTempo"])){
        $session_role=Select_Partie($room,$_SESSION["user_id"],false,1);
        if ($session_role){
            $session_role = $session_role[0];
            $data['user_pseudo'] = $_SESSION["user_pseudo"];
            $data['cible'] = $_GET["cible"];
            if ($_GET["role"]==2 && $session_role->role_id==2){
                $pusher->trigger($room, 'lgVote', $data);
            }
            elseif($_GET["role"]==1){
                $pusher->trigger($room, 'village', $data);
            }
        }
    }elseif(isset($_SESSION["user_id"])){
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("SELECT R.role_nom FROM partie AS P INNER JOIN role AS R ON P.role_id = R.role_id WHERE room_code = :code AND joueur_id = :id");
        $pdoStatement->execute([
            ":code" => $room,
            ":id" => $_SESSION["user_id"]
        ]);
        $result = $pdoStatement->fetch();
        echo json_encode($result);
    }
}