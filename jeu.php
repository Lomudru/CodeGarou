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
<link rel="stylesheet" href="<?= PROJECT_FOLDER ?>assets/css/jeu.css">
<body>
    <?php if(isset($_GET["code"]) && isset($_SESSION["user_id"])): ?>
        <?php 
            $pdo = connectToDbAndGetPdo();
            $pdoStatement = $pdo->prepare("SELECT R.room_code, R.room_nom, R.room_en_jeu, R.room_visibilite, R.room_nbr_max, COUNT(P.joueur_id) AS nbr_actuel FROM room AS R INNER JOIN partie AS P ON R.room_code = P.room_code WHERE R.room_code = :code;");
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

                    $data["room_code"] = $verif_partie->room_code;
                    $data["room_nom"] = $verif_partie->room_nom;
                    $data["nbr_max"] = $verif_partie->room_nbr_max;
                    $data["visibility"] = $verif_partie->room_visibilite;
                    $data["nbr_joueur"] = $verif_partie->nbr_actuel+1;
                    $pusher->trigger("Channel_room", 'room', $data);

                    $_SESSION["last_room_code"] = $verif_partie->room_code;
                }
            endif;
        ?>
        <?php
        $result = Select_Partie($_GET["code"],$_SESSION["user_id"]);
        if ($result != false){
            $spectator = true;
        }
        $en_jeu = Select_Room(false,$_GET["code"])[0];
        ?>
        <header>
            <div>
            <?php if ($spectator && $en_jeu->room_en_jeu != 1):?>
                <button id="lauch">Jouer</button>
                <p id="roleZone" class="hidden">Role</p>
            <?php elseif($spectator): ?>
                <p id="roleZone" class="hidden">Role</p>
            <?php endif;?>
            </div>
            <div id="codeHolder">
                <p id="code" onclick="copyText(this)"><?= $_GET["code"] ?></p>
                <i class="fa-solid fa-eye-slash" id="hideCode"></i>
            </div>
            <a id="quitter" href="<?= PROJECT_FOLDER ?>jouer.php">Quitter</a>
        </header>
    <?php else:
        header("Location: ".PROJECT_FOLDER."jouer.php");
    endif;
    ?>
    
    <main>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>     
        <div>
            <div id="listPlayer"></div>
            <div id="allActionHolder">
                <div>
                    <p>Deroulement du jeu</p>
                </div>
                <div id="allAction"></div>
            </div>
        </div>
        <div id="chatHolder">
            <i class="fa-solid fa-caret-down" id="hideChat"></i>
            <p>Message</p>
            <div id="chat"></div>
            <?php if ($spectator):?>
                <div>
                    <input type="text" placeholder="Votre message" name="chatBox" id="chatBox">
                    <i class="fa-solid fa-paper-plane" id="submit"></i>
                </div>
            <?php endif;?>
        </div>
    </main>
    <script src="<?= PROJECT_FOLDER ?>assets/js/jeu.js"></script>
    <script>
        let sessionId = <?= $_SESSION["user_id"]; ?>;
        function copyText(element) {
            // Access the innerText of the element
            var textToCopy = element.innerText;

            // Attempt to use the Clipboard API
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy)
                .then(function() {
                    // Optionally, you can give feedback to the user
                    alert('Text copied to clipboard: ' + textToCopy);
                })
                .catch(function(error) {
                    console.error('Failed to copy text: ', error);
                });
            } else {
                // Fallback: Create a temporary textarea to copy the text
                var textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Optionally, you can give feedback to the user
                alert('Text copied to clipboard: ' + textToCopy);
            }
            }
    </script>
    <script src="<?= PROJECT_FOLDER ?>assets/js/codeGarou.js"></script>
</body>
</html>