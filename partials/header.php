<header>
    <?php 
    if (isset($_SESSION['user_id'])) {
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("SELECT joueur_pseudo FROM joueur WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION['user_id'],
        ]);
        $data = $pdoStatement->fetch(); ?>
        <h1>Bonjour <?= $data->joueur_pseudo ?></h1>
        <a href="<?= PROJECT_FOLDER ?>action/disconnect.php">Se deconnecter</a>
    <?php }else{ ?>
        <h1>header</h1>
    <?php } ?>
    <?php 
    if(isset($_SESSION["user_id"]) && $_SERVER['PHP_SELF'] != "/CodeGarou/jeu.php"  && $_SERVER['PHP_SELF'] != "/CodeGarou/action/createRoom.php"){
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM partie WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION["user_id"]
        ]);
        $result = $pdoStatement->fetchAll();
    }
    ?>
</header>