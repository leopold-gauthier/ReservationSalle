<?php
session_start();
require "./include/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/signup.css">
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/9a09d189de.js" crossorigin="anonymous"></script>

    <title>Profil</title>
</head>

<body>
    <header>
        <img src="../assets/mysql-logo.png" alt="logo">
        <nav>
            <?php require './include/header-include.php' ?>
        </nav>
    </header>

    <main>

        <form method="post" action="profil.php">
            <h3>Edit</h3>

            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?= $_SESSION['users'][0]['login']  ?>" required />
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />
            <label for="cpassword">Confirmation</label>
            <input type="password" name="cpassword" id="cpassword" required />
            <?php
            if (isset($_POST['envoi'])) {
                $login = htmlspecialchars($_POST['login']);
                $password = $_POST['password']; // md5'() pour crypet le mdp
                $id = $_SESSION['users'][0]['id'];

                $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND id != ?");
                $recupUser->execute([$login, $id]);
                $insertUser = $bdd->prepare("UPDATE utilisateurs SET login = ? , prenom = ? , nom = ? , password=  ? WHERE id = ?");

                if (empty($login) || empty($password) || empty($_POST['cpassword'])) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspVeuillez complétez tous les champs.</p>";
                } elseif (!preg_match("#^[a-z0-9]+$#", $login)) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspLe login doit être renseigné en lettres minuscules sans accents, sans caractères spéciaux.</p>";
                } elseif ($password != md5($_POST['cpassword'])) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspLes deux mots de passe sont differents.</p>";
                } elseif ($password != $_SESSION['users'][0]['password']) {
                    echo  "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspCe n'est pas le bon mot de passe</p>";
                } elseif ($recupUser->rowCount() > 0) {
                    echo "<p><i class='fa-solid fa-triangle-exclamation'></i>&nbspCe login est déjà utilisé.</p>";
                } else {
                    $insertUser->execute([$login, $password, $id]);
                    $_SESSION['users'][0]['login'] = $login;
                    header("Location: profil.php");
                }
            }
            ?>
            <input type="submit" name="envoi" id="button" value="Edit">
        </form>

    </main>
</body>

</html>