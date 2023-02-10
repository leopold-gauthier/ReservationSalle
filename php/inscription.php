<?php
session_start();
require("./include/config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("./include/head-include.php"); ?>

    <title>Inscription</title>
</head>

<body>
    <header>
        <nav>
            <?php require('./include/header-include.php') ?>
        </nav>
    </header>

    <main>

        <form method="POST" action="">
            <h3>Sign Up</h3>

            <label for="login">Login</label>
            <input type="text" id="login" name="login" placeholder="Login" required autofocus autocomplete="off">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <label for="cpassword">Confirmation Password</label>
            <input type="password" id="cpassword" name="cpassword" placeholder="Confirmation" required>
            <?php
            if (isset($_POST['envoi'])) {
                $login = htmlspecialchars($_POST['login']);
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];

                $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
                $recupUser->execute([$login]);

                if (empty($login) || empty($password) || empty($cpassword)) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspVeuillez complétez tous les champs.</p>";
                } elseif (!preg_match("#^[a-z0-9]+$#", $login)) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspLe login doit être renseigné en lettres minuscules sans accents, sans caractères spéciaux.</p>";
                } elseif ($password != $cpassword) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspLes deux mots de passe sont differents.</p>";
                } elseif ($recupUser->rowCount() > 0) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspCe login est déjà utilisé.</p>";
                } else {
                    $insertUser = $bdd->prepare("INSERT INTO utilisateurs(login, password)VALUES(?,?)");
                    $insertUser->execute([$login, $password]);
                    header("Location: connexion.php");
                }
            }
            ?>
            <input type="submit" name="envoi" class="button" value="Sign Up">
        </form>
    </main>


    <footer><?php include_once("./include/footer.php"); ?></footer>

</body>

</html>