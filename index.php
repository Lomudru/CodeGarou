<?php require "utils/common.php" ?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<body>
    <?php require SITE_ROOT . "partials/header.php" ?>
    <main class="main">
        <?php if(isset($_SESSION["user_id"])): ?>
            <a href="<?= PROJECT_FOLDER ?>jouer.php" class="button" id="jouer">Jouer</a>
        <?php endif; ?>
        <p class="cadre">Bienvenue dans notre version en ligne du célèbre jeu de société, le Loup-Garou ! Plongez-vous dans une expérience palpitante où le mensonge, la manipulation et la déduction sont les clés de la survie.<br><br>Dans ce jeu immersif, vous serez plongé au cœur d'un village en proie à des attaques de Loups-Garous. Chaque joueur se voit attribuer un rôle secret : soit vous êtes un innocent Villageois cherchant à démasquer les Loups-Garous, soit vous êtes un redoutable Loup-Garou, complotant pour éliminer les Villageois.<br><br>Le jour, les Villageois se rassemblent pour débattre, voter et éliminer celui qu'ils soupçonnent d'être un Loup-Garou. Mais attention, les Loups-Garous se cachent parmi eux, prêts à semer la discorde et à éliminer leurs ennemis.<br><br>La nuit tombe, et les Loups-Garous sortent de leur tanière pour traquer leur prochaine victime. Les Villageois doivent rester vigilants et utiliser leur astuce pour identifier les Loups-Garous avant qu'il ne soit trop tard.<br><br>Avec des graphismes captivants et une interface conviviale, notre jeu de Loup-Garou en ligne offre une expérience divertissante et immersive pour les amateurs de jeux de rôle et de stratégie.<br><br>Prêt à rejoindre la bataille pour la survie du village ? Rejoignez-nous dès maintenant et découvrez qui est véritablement un ami ou un ennemi dans le monde mystérieux des Loups-Garous !</p>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
</body>
</html>