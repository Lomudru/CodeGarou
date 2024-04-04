<?php

function Insert_Chat($room_code = null, $joueur_id = null, $chat_msg = null, $chat_type = "G"){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO `chat`(`room_code`, `joueur_id`, `chat_msg`, `chat_type`) VALUES (:room_code,:joueur_id, :chat_msg, :chat_type)");
    $pdoStatement->execute([
        ":room_code" => $room_code,
        ":joueur_id" => $joueur_id,
        ":chat_msg" => $chat_msg,
        ":chat_type" => $chat_type
    ]);
}
function Select_Chat($room_code = False, $joueur_id = False, $chat_msg = False, $chat_type = False){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $chat_msg ) $params['chat_msg'] = $chat_msg;
    if( $chat_type ) $params['chat_type'] = $chat_type;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM chat WHERE $query");
    $pdoStatement->execute($params);

    return $pdoStatement->fetchAll();
}
function Update_Chat($room_code = False, $joueur_id = False, $chat_msg = False, $chat_type = False,$room_code_requete = False, $joueur_id_requete = False, $chat_msg_requete = False, $chat_type_requete = False){
    // Prepare query
    $query  = "";
    $query_requete  = "";
    $params = [];
    $params_requete = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $chat_msg ) $params['chat_msg'] = $chat_msg;
    if( $chat_type ) $params['chat_type'] = $chat_type;
    if( $room_code_requete ) $params_requete['room_code_requete'] = $room_code_requete;
    if( $joueur_id_requete ) $params_requete['joueur_id_requete'] = $joueur_id_requete;
    if( $chat_msg_requete ) $params_requete['chat_msg_requete'] = $chat_msg_requete;
    if( $chat_type_requete ) $params_requete['chat_type_requete'] = $chat_type_requete;
    
    foreach($params as $key => $param):
        $query .= substr($key,0,-8)." = :$key, ";
    endforeach;

    foreach($params_requete as $key => $param):
        $query_requete .= "$key = :$key AND ";
    endforeach;

    $params+=$params_requete;
    $query_requete .= "1";
    $query = substr($query,0,-2);

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("UPDATE chat SET $query WHERE $query_requete");
    $pdoStatement->execute($params);
}
function Delete_Chat($room_code = False, $joueur_id = False, $chat_msg = False, $chat_type = False){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $chat_msg ) $params['chat_msg'] = $chat_msg;
    if( $chat_type ) $params['chat_type'] = $chat_type;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("DELETE FROM chat WHERE $query");
    $pdoStatement->execute($params);
}

function Insert_Partie($room_code = null, $joueur_id = null, $role_id = 1, $en_vie = 1){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO `partie`(`room_code`, `joueur_id`, `role_id`, `en_vie`) VALUES (:room_code, :joueur_id, :role_id, :en_vie)");
    $pdoStatement->execute([
        ":room_code" => $room_code,
        ":joueur_id" => $joueur_id,
        ":role_id" => $role_id,
        ":en_vie" => $en_vie
    ]);
}
function Select_Partie($room_code = false, $joueur_id = false, $role_id = false, $en_vie = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $role_id ) $params['role_id'] = $role_id;
    if( $en_vie ) $params['en_vie'] = $en_vie;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM partie WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}
function Update_Partie($room_code = false, $joueur_id = false, $role_id = false, $en_vie = false, $room_code_requete = false, $joueur_id_requete = false, $role_id_requete = false, $en_vie_requete = false){
    // Prepare query
    $query  = "";
    $query_requete  = "";
    $params = [];
    $params_requete = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = intval($joueur_id);
    if( $role_id ) $params['role_id'] = intval($role_id);
    if( $en_vie ) $query .= "en_vie = (NOT en_vie), ";
    if( $room_code_requete ) $params_requete['room_code_requete'] = $room_code_requete;
    if( $joueur_id_requete ) $params_requete['joueur_id_requete'] = $joueur_id_requete;
    if( $role_id_requete ) $params_requete['role_id_requete'] = $role_id_requete;
    if( $en_vie_requete ) $params_requete['en_vie_requete'] = $en_vie_requete;
    foreach($params as $key => $param):
            $query .= "$key = :$key, ";
    endforeach;

    foreach($params_requete as $key => $param):
        $query_requete .= str_replace("_requete", "", $key)." = :$key AND ";
    endforeach;

    $params+=$params_requete;
    $query_requete .= "1";
    $query = substr($query,0,-2);

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("UPDATE partie SET $query WHERE $query_requete");
    $pdoStatement->execute($params);
}
function Delete_Partie($room_code = false, $joueur_id = false, $role_id = false, $en_vie = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $role_id ) $params['chat_msg'] = $role_id;
    if( $en_vie ) $params['chat_type'] = $en_vie;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("DELETE FROM partie WHERE $query");
    $pdoStatement->execute($params);
}

function Insert_Room($room_nom = null, $room_code = null, $room_visibilite = 1, $room_nbr_max = null, $room_en_jeu = 0, $room_time = null, $room_id_role_actuelle = 1){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO `room`(`room_nom`, `room_code`, `room_visibilite`, `room_nbr_max`, `room_en_jeu`, `room_time`, `room_id_role_actuelle`) VALUES (:room_nom, :room_code, :room_visibilite, :room_nbr_max, :room_en_jeu, :room_time, :room_id_role_actuelle)");
    $pdoStatement->execute([
        ":room_nom" => $room_nom,
        ":room_code" => $room_code,
        ":room_visibilite" => $room_visibilite,
        ":room_nbr_max" => $room_nbr_max,
        ":room_en_jeu" => $room_en_jeu,
        ":room_time" => $room_time,
        ":room_id_role_actuelle" => $room_id_role_actuelle
    ]);
}
function Select_Room($room_nom = False, $room_code = False, $room_visibilite = False, $room_nbr_max = False, $room_en_jeu = False, $room_time = False, $room_id_role_actuelle = False){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_nom ) $params['room_nom'] = $room_nom;
    if( $room_code ) $params['room_code'] = $room_code;
    if( $room_visibilite ) $params['room_visibilite'] = $room_visibilite;
    if( $room_nbr_max ) $params['room_nbr_max'] = $room_nbr_max;
    if( $room_en_jeu ) $params['room_en_jeu'] = $room_en_jeu;
    if( $room_time ) $params['room_time'] = $room_time;
    if( $room_id_role_actuelle ) $params['room_id_role_actuelle'] = $room_id_role_actuelle;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM room WHERE $query");
    $pdoStatement->execute($params);

    return $pdoStatement->fetchAll();
}
function Update_Room($room_nom = False, $room_code = False, $room_visibilite = False, $room_nbr_max = False, $room_en_jeu = False, $room_time = False, $room_id_role_actuelle = False, $room_nom_requete = False, $room_code_requete = False, $room_visibilite_requete = False, $room_nbr_max_requete = False, $room_en_jeu_requete = False, $room_time_requete = False, $room_id_role_actuelle_requete = False){
    // Prepare query
    $query  = "";
    $query_requete  = "";
    $params = [];
    $params_requete = [];
    if( $room_nom ) $params['room_nom'] = $room_nom;
    if( $room_code ) $params['room_code'] = $room_code;
    if( $room_visibilite ) $params['room_visibilite'] = $room_visibilite;
    if( $room_nbr_max ) $params['room_nbr_max'] = $room_nbr_max;
    if( $room_en_jeu ) $params['room_en_jeu'] = $room_en_jeu;
    if( $room_time ) $params['room_time'] = $room_time;
    if( $room_id_role_actuelle ) $params['room_id_role_actuelle'] = $room_id_role_actuelle;
    if( $room_nom_requete ) $params_requete['room_nom_requete'] = $room_nom_requete;
    if( $room_code_requete ) $params_requete['room_code_requete'] = $room_code_requete;
    if( $room_visibilite_requete ) $params_requete['room_visibilite_requete'] = $room_visibilite_requete;
    if( $room_nbr_max_requete ) $params_requete['room_nbr_max_requete'] = $room_nbr_max_requete;
    if( $room_en_jeu_requete ) $params_requete['room_en_jeu_requete'] = $room_en_jeu_requete;
    if( $room_time_requete ) $params_requete['room_time_requete'] = $room_time_requete;
    if( $room_id_role_actuelle_requete ) $params_requete['room_id_role_actuelle_requete'] = $room_id_role_actuelle_requete;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key, ";
    endforeach;

    foreach($params_requete as $key => $param):
        $query_requete .= substr($key,0,-8)." = :$key AND ";
    endforeach;

    $params+=$params_requete;
    $query_requete .= "1";
    $query = substr($query,0,-2);

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("UPDATE room SET $query WHERE $query_requete");
    $pdoStatement->execute($params);
}
function Delete_Room($room_nom = False, $room_code = False, $room_visibilite = False, $room_nbr_max = False, $room_en_jeu = False, $room_time = False, $room_id_role_actuelle = False){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_nom ) $params['room_nom'] = $room_nom;
    if( $room_code ) $params['room_code'] = $room_code;
    if( $room_visibilite ) $params['room_visibilite'] = $room_visibilite;
    if( $room_nbr_max ) $params['room_nbr_max'] = $room_nbr_max;
    if( $room_en_jeu ) $params['room_en_jeu'] = $room_en_jeu;
    if( $room_time ) $params['room_time'] = $room_time;
    if( $room_id_role_actuelle ) $params['room_id_role_actuelle'] = $room_id_role_actuelle;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("DELETE FROM room WHERE $query");
    $pdoStatement->execute($params);
}

function Insert_Joueur($joueur_pseudo = null, $joueur_mdp = null){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO `joueur`(joueur_pseudo, joueur_mdp) VALUES (:joueur_pseudo,:joueur_mdp)");
    $pdoStatement->execute([
        ":joueur_pseudo" => $joueur_pseudo,
        ":joueur_mdp" => $joueur_mdp
    ]);
}
function Select_Joueur($joueur_id = false, $joueur_pseudo = false, $joueur_mdp = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $joueur_pseudo ) $params['joueur_pseudo'] = $joueur_pseudo;
    if( $joueur_mdp ) $params['joueur_mdp'] = $joueur_mdp;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM joueur WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}
function Update_Joueur($joueur_id = false, $joueur_pseudo = false, $joueur_mdp = false, $joueur_id_requete = false, $joueur_pseudo_requete = false, $joueur_mdp_requete = false){
    // Prepare query
    $query  = "";
    $query_requete  = "";
    $params = [];
    $params_requete = [];
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    if( $joueur_pseudo ) $params['joueur_pseudo'] = $joueur_pseudo;
    if( $joueur_mdp ) $params['joueur_mdp'] = $joueur_mdp;
    if( $joueur_id_requete ) $params_requete['joueur_id_requete'] = $joueur_id_requete;
    if( $joueur_pseudo_requete ) $params_requete['joueur_pseudo_requete'] = $joueur_pseudo_requete;
    if( $joueur_mdp_requete ) $params_requete['joueur_mdp_requete'] = $joueur_mdp_requete;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key, ";
    endforeach;

    foreach($params_requete as $key => $param):
        $query_requete .= substr($key,0,-8)." = :$key AND ";
    endforeach;

    $params+=$params_requete;
    $query_requete .= "1";
    $query = substr($query,0,-2);

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("UPDATE joueur SET $query WHERE $query_requete");
    $pdoStatement->execute($params);
}
function Delete_Joueur($joueur_id = false, $joueur_pseudo = false, $joueur_mdp = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $joueur_pseudo ) $params['joueur_pseudo'] = $joueur_pseudo;
    if( $joueur_mdp ) $params['joueur_mdp'] = $joueur_mdp;
    if( $joueur_id ) $params['joueur_id'] = $joueur_id;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("DELETE FROM joueur WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}

function Insert_Interaction($room_code = null, $tour = null, $acteur_id = null, $action = null, $victime_id = null){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("INSERT INTO `interaction`(`room_code`, `tour`, `acteur_id`, `action`, `victime_id`) VALUES (:room_code, :tour, :acteur_id, :action_role, :victime_id)");
    $pdoStatement->execute([
        ":room_code" => $room_code,
        ":tour" => $tour,
        ":acteur_id" => $acteur_id,
        ":action_role" => $action,
        ":victime_id" => $victime_id,
    ]);
}
function Select_Interaction($room_code = false, $tour = false, $acteur_id = false, $action = false, $victime_id = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $tour ) $params['tour'] = $tour;
    if( $acteur_id ) $params['acteur_id'] = $acteur_id;
    if( $action ) $params['action'] = $action;
    if( $victime_id ) $params['victime_id'] = $victime_id;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM interaction WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}
function Update_Interaction($room_code = false, $tour = false, $acteur_id = false, $action = false, $victime_id = false, $room_code_requete = false, $tour_requete = false, $acteur_id_requete = false, $action_requete = false, $victime_id_requete = false){
    // Prepare query
    $query  = "";
    $query_requete  = "";
    $params = [];
    $params_requete = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $tour ) $params['tour'] = $tour;
    if( $acteur_id ) $params['acteur_id'] = $acteur_id;
    if( $action ) $params['action'] = $action;
    if( $victime_id ) $params['victime_id'] = $victime_id;
    if( $room_code_requete ) $params['room_code_requete'] = $room_code_requete;
    if( $tour_requete ) $params['tour_requete'] = $tour_requete;
    if( $acteur_id_requete ) $params['acteur_id_requete'] = $acteur_id_requete;
    if( $action_requete ) $params['action_requete'] = $action_requete;
    if( $victime_id_requete ) $params['victime_id_requete'] = $victime_id_requete;
    
    foreach($params as $key => $param):
        $query .= substr($key,0,-8)." = :$key, ";
    endforeach;

    foreach($params_requete as $key => $param):
        $query_requete .= "$key = :$key AND ";
    endforeach;

    $params+=$params_requete;
    $query_requete .= "1";
    $query = substr($query,0,-2);

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM interaction WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}
function Delete_Interaction($room_code = false, $tour = false, $acteur_id = false, $action = false, $victime_id = false){
    // Prepare query
    $query  = "";
    $params = [];
    if( $room_code ) $params['room_code'] = $room_code;
    if( $tour ) $params['tour'] = $tour;
    if( $acteur_id ) $params['acteur_id'] = $acteur_id;
    if( $action ) $params['action'] = $action;
    if( $victime_id ) $params['victime_id'] = $victime_id;
    
    foreach($params as $key => $param):
        $query .= "$key = :$key AND ";
    endforeach;

    $query .= "1";

    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("DELETE FROM interaction WHERE $query");
    $pdoStatement->execute($params);
    return $pdoStatement->fetchAll();
}

function Prochain_role($room){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare(
        "SELECT P.role_id AS role_suivant FROM partie AS P
        INNER JOIN role AS R ON P.role_id = R.role_id
        WHERE P.room_code = :code
        AND P.en_vie = 1
        AND R.role_ordre > (
            SELECT R.role_ordre FROM role AS R
            INNER JOIN room AS M ON R.role_id = M.room_id_role_actuelle
            WHERE R.role_id = M.room_id_role_actuelle)
        ORDER BY R.role_ordre ASC
        LIMIT 1;");
    $pdoStatement->execute([
        ":code" => $room
    ]);
    $areturn = $pdoStatement->fetch();
    if ($areturn){
        return $areturn->role_suivant;
    }else{
        return 1;
    }
}

function joueur_executant($room){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare(
        "SELECT IFNULL((SELECT P.joueur_id FROM partie AS P
        INNER JOIN room AS R ON P.room_code = R.room_code
        WHERE P.role_id = R.room_id_role_actuelle AND P.room_code=:code
        LIMIT 1), (SELECT joueur_id FROM partie WHERE room_code=:code LIMIT 1)) AS joueur_id;");
    $pdoStatement->execute([
        ":code" => $room
    ]);
    $areturn = $pdoStatement->fetch();
    return $areturn->joueur_id;
}

function faire_mourir($room, $action, $tour){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT J.joueur_pseudo, I.victime_id FROM interaction AS I INNER JOIN Joueur AS J ON I.victime_id = J.joueur_id WHERE I.action = :action_role AND I.room_code = :code AND I.tour = :tour GROUP BY I.victime_id ORDER BY COUNT(I.victime_id) DESC, RAND() LIMIT 1");
    $pdoStatement->execute([
        ":code" => $room,
        ":action_role" => $action,
        ":tour" => $tour
    ]);
    $areturn = $pdoStatement->fetch();
    if($areturn){
        return $areturn;
    }else{
        return false;
    }
}

function victoire($room){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT R.role_camp FROM partie AS P INNER JOIN role AS R ON P.role_id = R.role_id WHERE P.room_code = :code AND P.en_vie = 1 GROUP BY R.role_camp;");
    $pdoStatement->execute([
        ":code" => $room
    ]);
    $areturn = $pdoStatement->fetchall();
    if(count($areturn) > 1){
        return false;
    }else{
        return $areturn[0];
    }
}