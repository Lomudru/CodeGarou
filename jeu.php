<?php require "utils/common.php" ?>
<!DOCTYPE html>
<html lang="fr">
<?php require SITE_ROOT . "partials/head.php" ?>
<body>
    <?php require SITE_ROOT . "partials/header.php" ?>
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
            elseif($verif_partie->room_en_jeu == 1):
                header("Location: ".PROJECT_FOLDER."jouer.php");
            else:
                $pdoStatement = $pdo->prepare("SELECT joueur_id FROM partie WHERE joueur_id = :id");
                $pdoStatement->execute([
                    ":id" => $_SESSION["user_id"],
                ]);
                $verif_id = $pdoStatement->fetch();
                if($verif_id == false && $verif_partie->nbr_actuel < $verif_partie->room_nbr_max){
                    $pdoStatement = $pdo->prepare("INSERT INTO partie VALUE (:code,:id_joueur,1,1)");
                    $pdoStatement->execute([
                        ":code" => $verif_partie->room_code,
                        ":id_joueur" => $_SESSION["user_id"],
                    ]);
                }
            endif;
            ?>
    <?php else:
        header("Location: ".PROJECT_FOLDER."jouer.php");
    endif;
    ?>
    <main>
        <div id="listPlayer"></div>
        <div id="chat"></div>
        <input type="text" placeholder="Message" name="chatBox" id="chatBox">
        <a href="<?= PROJECT_FOLDER ?>jouer.php">Quitter</a>
    </main>
    <?php require SITE_ROOT . "partials/footer.php" ?>
    <script src="<?= PROJECT_FOLDER ?>assets/js/jeu.js"></script>
</body>
</html>