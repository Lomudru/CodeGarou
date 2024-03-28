<?php
require "../utils/common.php";
require "../utils/functions.php";
if(!empty($_POST)){
    $dataError = Select_Joueur(false,$_POST['pseudo'])[0];
    if($dataError==false){
        $password = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
        Insert_Joueur($_POST['pseudo'],$password);
        $data = Select_Joueur(false,$_POST['pseudo'])[0];
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