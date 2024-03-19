<?php
require "../utils/common.php";

if(!empty($_POST)){
    $pdo = connectToDbAndGetPdo();
    $pdoStatement = $pdo->prepare("SELECT * FROM joueur WHERE joueur_pseudo = :pseudo");
    $pdoStatement->execute([
        ":pseudo" => $_POST['pseudo'],
    ]);
    $joueur = $pdoStatement->fetch();
    if ($joueur != false) {
        if (password_verify($_POST["mdp"], $joueur->joueur_mdp)){
            $_SESSION["user_id"]= $joueur->joueur_id;
            $_SESSION["user_pseudo"]= $joueur->joueur_pseudo;
            header("Location: ". PROJECT_FOLDER ."index.php");
        }
        else {
            header("Location: ". PROJECT_FOLDER ."login.php?error=incorrect");
        }
    } else {
        header("Location: ". PROJECT_FOLDER ."login.php?error=inexistant");
    }
    
} else{
    header("Location: ". PROJECT_FOLDER ."index.php");
}