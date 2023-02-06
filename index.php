<?php
session_start();
require "./php/include/config.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/origin-main.css">
    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/9a09d189de.js" crossorigin="anonymous"></script>
    <title>Index</title>
</head>

<body>
    <header>
        <nav>
            <?php
            if (isset($_SESSION['login'])) {
                echo '<a href="./index.php">Accueil</a>';
                echo '<a href="./php/profil.php">Profil</a>';
                echo '<a href="./php/planning.php">Planning</a>';
                echo '<a href="./php/reservation-form.php">Réservation</a>';

                if ($_SESSION['login'] == 'admin') {
                    echo '<a href="./php/admin.php">Admin</a>';
                }
                echo '<a href="./php/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
            } else {
                echo '<a href="./index.php">Accueil</a>';
                echo '<a href="./php/connexion.php">Se connecter</a>';
                echo "<a href='./php/inscription.php'>S'inscrire</a>";
                echo "<a href='./php/planning.php'>Planning</a>";
            }
            ?>
        </nav>
    </header>

    <main>
        <h1>Bienvenue<br>
            <?php
            if (isset($_SESSION['login'])) {
                echo strtoupper($_SESSION['login']);
            }
            ?>
        </h1>
        <style>
            h1 {
                text-align: center;
                font-size: 5rem;
                margin: 5% 0 0;
            }
        </style>
    </main>
</body>

</html>