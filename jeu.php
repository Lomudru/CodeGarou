<?php 
require "utils/common.php";
require 'vendor/autoload.php';
require "utils/functions.php";
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

?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<body>
    <?php if(isset($_GET["code"]) && isset($_SESSION["user_id"])): ?>
        <p id="code"><?= $_GET["code"] ?></p>
        <?php 
            $pdo = connectToDbAndGetPdo();
            $pdoStatement = $pdo->prepare("SELECT R.room_code, R.room_en_jeu, R.room_nbr_max, COUNT(P.joueur_id) AS nbr_actuel FROM room AS R INNER JOIN partie AS P ON R.room_code = P.room_code WHERE R.room_code = :code;");
            $pdoStatement->execute([
                ":code" => $_GET["code"],
            ]);
            $verif_partie = $pdoStatement->fetch();
            if($verif_partie == false || $verif_partie->room_code == NULL):
                header("Location: ".PROJECT_FOLDER."jouer.php?error=code");
            elseif(!$verif_partie->room_en_jeu == 1):
                $verif_id = Select_Partie(false,$_SESSION["user_id"]);
                if($verif_id == false && $verif_partie->nbr_actuel < $verif_partie->room_nbr_max){
                    Insert_Partie($verif_partie->room_code,$_SESSION["user_id"]);
                    $data['user_pseudo'] = $_SESSION["user_pseudo"];
                    $pusher->trigger($verif_partie->room_code, 'player', $data);
                    $_SESSION["last_room_code"] = $verif_partie->room_code;
                }
            endif;
            ?>
    <?php else:
        header("Location: ".PROJECT_FOLDER."jouer.php");
    endif;
    ?>
    <?php
    $result = Select_Partie($_GET["code"],$_SESSION["user_id"]);
    if ($result != false){
        $spectator = true;
    }
    $en_jeu = Select_Room(false,$_GET["code"])[0];

    ?>
    <main>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <?php if ($spectator && $en_jeu->room_en_jeu != 1):?>
            <button id="lauch">Jouer</button>
        <?php endif;?>
        <h2>Player</h2>
        <div id="listPlayer"></div>
        <h2>Chat</h2>
        <div id="chat"></div>
            <?php if ($spectator):?>
                <input type="text" placeholder="Message" name="chatBox" id="chatBox">
            <?php endif;?>
        <a href="<?= PROJECT_FOLDER ?>jouer.php">Quitter</a>
    </main>
    <script src="<?= PROJECT_FOLDER ?>assets/js/jeu.js"></script>
    <script>
        let sessionId = <?= $_SESSION["user_id"]; ?>;
    </script>
    <script src="<?= PROJECT_FOLDER ?>assets/js/codeGarou.js"></script>
</body>
</html>