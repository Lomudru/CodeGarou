
<header>
    <?php 
    require 'vendor/autoload.php';
    require 'utils/functions.php';
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
        $pdoStatement = $pdo->prepare("SELECT room_code from partie WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION["user_id"]
        ]);
        $result=$pdoStatement->fetch();
        $pdoStatement = $pdo->prepare("DELETE FROM partie WHERE joueur_id = :id");
        $pdoStatement->execute([
            ":id" => $_SESSION["user_id"]
        ]);
        if($result){
            $room = Select_Room(false,$result->room_code)[0];
            $data["room_code"] = $result->room_code;
            $data["room_nom"] = $room->room_nom;
            $data["nbr_max"] = $room->room_nbr_max;
            $data["visibility"] = $room->room_visibilite;
            $data["nbr_joueur"] = playeringame($result->room_code)->nbr_joueur;
            $pusher->trigger("Channel_room", 'room', $data);
        }
        
        if(isset($_SESSION["last_room_code"])){
            $data["user_pseudo"] = $_SESSION["user_pseudo"];
            $data["disconnect"] = true;
            $pusher->trigger($_SESSION["last_room_code"], 'player', $data);
        }
    }
    ?>
</header>