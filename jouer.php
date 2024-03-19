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
<body>
    <?php require SITE_ROOT . "partials/header.php" ?>
    <main>
        <?php if(isset($_GET["error"])): ?>
            <p>Le code est inccorect</p>
        <?php endif; ?>
        <form action="<?= PROJECT_FOLDER?>action/createRoom.php" method="POST">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" value="<?= $_SESSION["user_pseudo"]?>'s game">
            <label for="public">Public:</label>
            <input type="radio" name="visibilite" id="public" value=1 checked>
            <label for="prive">Privé:</label>
            <input type="radio" name="visibilite" id="prive" value=0>
            <label for="nbr_joueur_max">Nombre de joueur max:</label>
            <input type="number" name="nbr_joueur_max" id="nbr_joueur_max" value=11 min=4 max=30>
            <button type="submit">Générer</button>
        </form>
        <form action="jouer.php" id="search" method="get">
            <input type="text" name="code" id="code" placeholder="code">
        </form>
        <div id="allRoom"></div>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
    <script src="<?= PROJECT_FOLDER ?>assets/js/room.js"></script>
</body>
</html>