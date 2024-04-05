<?php 
require "utils/common.php";
if(!isset($_SESSION["user_id"])){
    header("Location: ".PROJECT_FOLDER."index.php");
}
if(isset($_GET["code"])){
    header("Location: ".PROJECT_FOLDER."jeu.php?code=".$_GET["code"]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<link rel="stylesheet" href="assets/css/jouer.css">
<link rel="stylesheet" href="assets/css/normalize.css">
<body>
    <?php require SITE_ROOT . "partials/header.php" ?>
    <main>
        <?php if(isset($_GET["error"])): ?>
            <p class="codeInccorect">Le code est incorrect</p>
        <?php endif; ?>
        <div id="formall">
            <div class="form" id="creationRoom">
                <form action="<?= PROJECT_FOLDER?>action/createRoom.php" method="POST">
                    <label for="nom">Nom de la room : </label>
                    <input type="text" name="nom" id="nom" value="<?= $_SESSION["user_pseudo"]?>'s game">
                    <p>Visibilité :</p>
                    <p class="visible">
                        <label for="public">Public:</label>
                        <input type="radio" name="visibilite" id="public" value=1 checked></p>
                    <p class="visible">
                        <label for="prive">Privé:</label>
                        <input type="radio" name="visibilite" id="prive" value=0>
                    </p>
                    <label for="nbr_joueur_max">Nombre de joueur max:</label>
                    <input type="number" name="nbr_joueur_max" id="nbr_joueur_max" value=11 min=4 max=30>
                    <button type="submit" class="button">Générer</button>
                </form>
            </div>
            <div class="form" id="rejoindreRoom">
                <form action="jouer.php" id="search" method="get">
                    <label for="code">Rejoindre une room avec son code :</label>
                    <input type="text" name="code" id="code" placeholder="code">
                </form>
            </div>
        </div>
        <div id="allRoom"></div>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="<?= PROJECT_FOLDER ?>assets/js/room.js"></script>
</body>
</html>