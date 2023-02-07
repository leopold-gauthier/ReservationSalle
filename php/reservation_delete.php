<?php
session_start();
include_once "./include/config.php";


// deuxieme condition pour vérifier si c'est bien de ce commentaire dont on parle
// troisieme condition éxecuter le suppresion
if (isset($_GET['id']) and !empty($_GET['id'])) {

    $suppr_id = htmlspecialchars($_GET['id']);

    $suppr = $bdd->prepare('DELETE FROM reservations WHERE id = ?');
    $suppr->execute(array($suppr_id));

    header('Location: ./planning.php');
}
