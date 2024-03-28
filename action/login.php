<?php
require "../utils/common.php";
require "../utils/functions.php";

if(!empty($_POST)){
    $joueur = Select_Joueur(false,$_POST['pseudo'])[0];
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