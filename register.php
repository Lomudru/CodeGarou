<?php 
require "utils/common.php";
if(isset($_SESSION["user_id"])){
    header("Location: ". PROJECT_FOLDER ."index.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<body class="compte">
    <?php require SITE_ROOT . "partials/header.php" ?>
    <main class="main">
        <?php 
            if(isset($_GET["error"])){?>
                <p>Pseudo deja pris</p>
        <?php } ?>
        <form action="<?= PROJECT_FOLDER ?>action/register.php" method="post" class="cadre">
            <label for="pseudo">pseudo :</label>
            <input type="text" name="pseudo" id="pseudo">
            <label for="mdp">mdp :</label>
            <input type="password"  name="mdp" id="mdp">
            <input type="submit" value="s'inscrire" class="button">
        </form>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
</body>
</html>