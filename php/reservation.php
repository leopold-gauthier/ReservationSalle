<?php
session_start();
if (isset($_SESSION["login"])) {
    if (isset($_GET["evenement"]) && !empty($_GET["evenement"])) {
        $id = $_GET["evenement"];

        $connexionbd = mysqli_connect("localhost", "root", "", "reservationsalles");
        $requete = "SELECT * FROM reservations INNER JOIN utilisateurs ON utilisateurs.id = reservations.id_utilisateurs WHERE reservations.id = '$id'";
        $query = mysqli_query($connexionbd, $requete);
        $resa = mysqli_fetch_all($query, MYSQLI_ASSOC);

        $titre = $resa[0]['titre'];
        $login = $resa[0]['login'];
        $description = $resa[0]['description'];

        $debut = explode(" ", $resa[0]['debut']);

        $H = explode(":", $debut[1]);
        $heure_debut = $H[0] . ":" . $H[1]; //récupère seulement l'heure dans début      

        $J =  explode("-", $debut[0]);
        $jour = $J[2] . "-" . $J[1] . "-" . $J[0]; //récupère seuleument la date dans début, mais formter j-m-a                                       

        $fin = explode(" ", $resa[0]['fin']);

        $HF = explode(":", $fin[1]);
        $heure_fin = $HF[0] . ":" . $HF[1];
    }
} else {
    header("Location:planning.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" />
    <title>Réservation</title>
</head>

<body>
    <?php include 'include/header.php'; ?>

    <main>
        <section id="reservation">
            <h1>Réserver par <u><?php echo $login; ?></u></h1>
            <p>Le <?php echo $jour ?> de <?php echo $heure_debut; ?> à <?php echo $heure_fin; ?></p>
            <section class="info_resa">
                <p><u>Intitulé</u> :</p>
                <?php echo $titre; ?>
            </section>
            <hr>
            <section class="info_resa">
                <p><u>Description</u> :</p>
                <?php echo $description; ?>
            </section>

        </section>
    </main>

    <?php include 'include/footer.php'; ?>
</body>

</html>