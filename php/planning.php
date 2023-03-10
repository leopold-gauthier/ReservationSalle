<?php
session_start();
require("./include/config.php");
//sélectionne toutes les réservations de la semaine en cour, en allant chercher le login de l'user qui fait la résa. 
$requete_resa = $bdd->prepare("SELECT * FROM utilisateurs INNER JOIN reservations ON utilisateurs.id = reservations.id_utilisateur WHERE week(debut) = week(curdate())");
$requete_resa->execute();
$info_resa = $requete_resa->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("./include/head-include.php"); ?>
    <title>Planning</title>
</head>

<body>
    <header>
        <nav>
            <?php require('./include/header-include.php') ?>
        </nav>
    </header>

    <div id="backgroundplanning">
        <main>

            <h1 class="bk_font">
                Planning <?php echo $jour_semaine = date('Y', time()); ?>
            </h1>
            <div id="nav_planning">
                <h2 class="bk_font">Semaine <?php echo $jour_semaine = date('W', time()); ?></h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="vide"></th>
                        <th class="jour">Lundi <?php echo $jour_semaine = date('d/m', strtotime('monday this week')); ?></th>
                        <th class="jour">Mardi <?php echo $jour_semaine = date('d/m', strtotime('tuesday this week')); ?></th>
                        <th class="jour">Mercredi <?php echo $jour_semaine = date('d/m', strtotime('wednesday this week')); ?></th>
                        <th class="jour">Jeudi <?php echo $jour_semaine = date('d/m', strtotime('thursday this week')); ?></th>
                        <th class="jour">Vendredi <?php echo $jour_semaine = date('d/m', strtotime('friday this week')); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($heure = 8; $heure <= 19; $heure++) //génération lignes des heures
                    {
                    ?>
                        <tr>
                            <td class="heure">
                                <p><?php echo $heure . "h"; ?></p>
                            </td>
                            <?php
                            for ($jour = 1; $jour <= 5; $jour++) //génération des cellules sous les jours
                            {
                                if (!empty($info_resa)) {
                                    foreach ($info_resa as $resa => $Hresa) //sépare les réservations
                                    {
                                        $JH = explode(" ", $Hresa["debut"]); //sélection la ligne correspondant à l'heure de début

                                        $H = explode(":", $JH[1]); //explose l'heure
                                        $heure_resa = date("G", mktime($H[0], $H[1], $H[2], 0, 0, 0)); //récupère uniquement l'heure sans le 0                  

                                        $J = explode("-", $JH[0]); //explose la date
                                        $jour_resa = date("N", mktime(0, 0, 0, $J[1], $J[2], $J[0])); //récupère le numéro du jour      

                                        $case_resa = $heure_resa . $jour_resa; //crée un numéro de réservation                     
                                        $case = $heure . $jour; //Crée un numéro pour chaque cellules


                                        $titre = $Hresa["titre"];
                                        $login = $Hresa["login"];
                                        $desc = $Hresa["description"];
                                        $id = $Hresa["id"];



                                        if ($case == $case_resa) {
                            ?>
                                            <td class="td_reserved">
                                                <a href="reservation.php?evenement=<?php echo $id; ?>">
                                                    <?php echo $titre; ?><br>
                                                    <i class="fa-sharp fa-solid fa-person-rifle"></i>
                                                    <?php //  echo $desc;
                                                    ?><br>
                                                    <?php
                                                    if (isset($_SESSION['login']) && $_SESSION['login'] == 'admin') { ?>
                                                        <a onclick="confirmToSuppr()"><i class="fa-solid fa-xmark"></i></a>
                                                    <?php
                                                    } else {
                                                        // echo "";
                                                    }
                                                    ?>
                                                </a>
                                            </td>
                                        <?php
                                            break;
                                        } else //si pas de correspondance set $case à null pour éviter trop d'affchage
                                        {
                                            $case = null;
                                        }
                                    }
                                    if ($case == null) {
                                        ?>
                                        <td class="case"><a href="reservation-form.php?heure_debut=<?php echo $heure; ?>&amp;date_debut=<?php echo $jour; ?>"><i class="fa-sharp fa-solid fa-pen"></i></a></td>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <td class="case"><a href="reservation-form.php?heure_debut=<?php echo $heure; ?>&amp;date_debut=<?php echo $jour; ?>"><i class="fa-sharp fa-solid fa-pen"></i></a></td>
                            <?php
                                }
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <footer><?php include_once("./include/footer.php"); ?></footer>

</body>

</html>

<STYLE>
    body {
        text-align: center;

    }
</STYLE>
<script>
    function confirmToSuppr() {
        if (window.confirm("Do you really want to delete ?")) {
            window.open("./reservation_delete.php?id=<?php echo $id; ?>");
        } else {
            window.open("../php/planning.php", "Cancel");

        }
    }
</script>