<?php
if (isset($_SESSION['login'])) {
    echo '<a href="./index.php">Accueil</a>';
    echo '<a href="./php/profil.php">Profil</a>';
    if ($_SESSION['login'] == 'admin') {
        echo '<a href="./php/admin.php">Admin</a>';
    }
    echo '<a href="./php/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
} else {
    echo '<a href="./index.php">Accueil</a>';
    echo '<a href="./php/connexion.php">Se connecter</a>';
    echo "<a href='./php/inscription.php'>S'inscrire</a>";
}
