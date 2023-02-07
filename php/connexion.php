<?php
session_start();
require "./include/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("./include/head-include.php"); ?>

    <title>Connexion</title>
</head>

<body>
    <header>
        <img src="../assets/mysql-logo.png" alt="logo">
        <nav>
            <?php require './include/header-include.php' ?>
        </nav>
    </header>
    <main>

        <form method="POST" action="">
            <h3>Login Here</h3>
            <label for="login">Username</label>
            <input type="text" id="login" name="login" placeholder="Login" required autofocus autocomplete="off">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder='Password' required autocomplete="off">
            <?php
            if (isset($_POST['envoi'])) {
                $login = htmlspecialchars($_POST['login']);
                $password = $_POST['password']; // md5'() pour crypet le mdp

                if (!empty($login) && !empty($password)) {
                    $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND password = ?");
                    $recupUser->execute([$login, $password]);

                    if ($recupUser->rowCount() > 0) {
                        $_SESSION['login'] = $login;
                        $_SESSION['password'] = $password;
                        $_SESSION['users'] = $recupUser->fetchAll(PDO::FETCH_ASSOC);
                        header("Location: ../index.php");
                    }
                } else {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspVotre login ou mot de passe incorect.</p>";
                }
            } else {
                echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspVeuillez compléter tous les champs.</p>";
            }

            ?>
            <input type="submit" name="envoi" value="Log In" id="button">
        </form>
    </main>

    <footer><a href="https://github.com/Dylan-olivro"><i class="fa-brands fa-github"></i></a></footer>

</body>

</html>