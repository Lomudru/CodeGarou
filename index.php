<?php require "utils/common.php" ?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<body>
    <?php require SITE_ROOT . "partials/header.php" ?>
    <main>
        <?php if(!isset($_SESSION["user_id"])): ?>
            <a href="<?= PROJECT_FOLDER ?>register.php">register</a>
            <a href="<?= PROJECT_FOLDER ?>login.php">login</a>
        <?php else: ?>
            <a href="<?= PROJECT_FOLDER ?>jouer.php">Jouer</a>
        <?php endif; ?>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
</body>
</html>