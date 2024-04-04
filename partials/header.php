
<header>
    <?php 
    require 'vendor/autoload.php';
    $options = array(
        'cluster' => 'eu',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        'e39c485e91fd48c1c3e7',
        '2dfef9c32cb8dd9d44cb',
        '1774389',
        $options
    );
    $spectator = false;
    if (isset($_SESSION['user_id'])) {
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("SELECT joueur_pseudo FROM joueur WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION['user_id'],
        ]);
        $pseudo = $pdoStatement->fetch(); ?>
        <h1>Bonjour <?= $pseudo->joueur_pseudo ?></h1>
        <a href="<?= PROJECT_FOLDER ?>action/disconnect.php" class="button">Se déconnecter</a>
    <?php }else{ ?>
        <h1>CodeGarou</h1>
        <div>
            <a href="<?= PROJECT_FOLDER ?>register.php" class="button">register</a>
            <a href="<?= PROJECT_FOLDER ?>login.php" class="button">login</a>
        </div>
    <?php } ?>
    <?php 
    if(isset($_SESSION["user_id"]) && $_SERVER['PHP_SELF'] != "/CodeGarou/jeu.php"  && $_SERVER['PHP_SELF'] != "/CodeGarou/action/createRoom.php"){
        $pdo = connectToDbAndGetPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM partie WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION["user_id"]
        ]);
        $result = $pdoStatement->fetchAll();
        if(isset($_SESSION["last_room_code"])){
            $data["user_pseudo"] = $_SESSION["user_pseudo"];
            $data["disconnect"] = true;
            $pusher->trigger($_SESSION["last_room_code"], 'player', $data);
        }
    }
    ?>
</header>