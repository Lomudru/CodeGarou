<?php
require "../utils/common.php";
if(!empty($_POST)){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT joueur_id FROM joueur WHERE joueur_pseudo = :pseudo");
    $pdoStatement->execute([
        ":pseudo" => $_POST['pseudo'],
    ]);
    $dataError = $pdoStatement->fetch();
    if($dataError==false){
        $password = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
        $pdoStatement = $pdo->prepare("INSERT INTO joueur(joueur_pseudo,joueur_mdp) VALUES (:pseudo, :mdp)");
        $pdoStatement->execute([
            ":pseudo" => $_POST['pseudo'],
            ":mdp" => $password
        ]);
        $pdoStatement = $pdo->prepare("SELECT joueur_id FROM joueur WHERE joueur_pseudo = :pseudo");
        $pdoStatement->execute([
            ":pseudo" => $_POST['pseudo'],
        ]);
        $data = $pdoStatement->fetch();
        $_SESSION["user_id"]= $data->joueur_id;
        $_SESSION["user_pseudo"]= $_POST["pseudo"];
        header("Location: ". PROJECT_FOLDER ."index.php");
    }
    else{
        header("Location: ". PROJECT_FOLDER ."register.php?error=pseudo");
    }
    
}else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}