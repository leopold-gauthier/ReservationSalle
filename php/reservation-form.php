<?php
session_start();
include_once "./include/config.php";
if (!isset($_SESSION['login'])) {
    header("Location: ./connexion.php");
} else {
    if (isset($_POST["validresa"]) && !empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_POST["debut_date"]) && !empty($_POST["debut_heure"]) && !empty($_POST["fin_date"]) && !empty($_POST["fin_heure"])) {
        $debut = $_POST["debut_date"] . " " . $_POST["debut_heure"];
        $fin = $_POST["fin_date"] . " " . $_POST["fin_heure"];
        $titre = $_POST["titre"];
        $description = $_POST["description"];
        $debut_str = strtotime($debut);
        $fin_str = strtotime($fin);
        $id = $_SESSION['users'][0]["id"];

        //vérifie que l'heure de début choisie n'est pas déjà enregistré
        // PDO
        $requete_creneau = $bdd->prepare('SELECT * FROM reservations WHERE debut= ?');
        $requete_creneau->execute(array($debut));
        $requete_creneau = $requete_creneau->fetch(PDO::FETCH_ASSOC);

        //check si le jour n'est pas week-end
        $semaine = explode("-", $_POST["debut_date"]);
        $jour = date("N", mktime(0, 0, 0, $semaine[1], $semaine[2], $semaine[0]));

        if (empty($requete_creneau)) {
            if ($debut_str < time()) //check date saisie pour début n'est pas déjà passée                                 
            //time -2 heures !!!!!                            
            {
                $msg_error =  "L'heure est déjà passée";
            } else {
                if ($_POST["debut_date"] == $_POST["fin_date"]) //vérifie que c'est le même jour
                {
                    $time_debut = explode(':', $_POST["debut_heure"]); //transforme l'heure en tableau 2 entrées                                     
                    $time_fin = explode(':', $_POST["fin_heure"]);

                    if ($fin_str < $debut_str) //check si date de fin n'est pas avant début                                                                
                    {
                        $msg_error = "La fin doit être le même jour";
                    } else if ($time_fin[0] - $time_debut[0] == 1) //regarde si le créneau dure 1h
                    {
                        if ($jour <= 5) //vérifie que le jour n'est pas week-end
                        {
                            $ajout = $bdd->prepare("INSERT INTO reservations (id_utilisateur,titre, description, debut, fin) VALUES (?, ?, ?, ?, ?)");
                            $ajout->execute([$id, $titre, $description, $debut, $fin]);
                            header("Location: ./planning.php");
                        } else {
                            $msg_error = "Pas de réservation le week-end";
                        }
                    } else {
                        $msg_error = "Le créneau doit faire 1h";
                    }
                } else {
                    $msg_error = "Date de début et de fin différente";
                }
            }
        } else {
            $msg_error = "Créneau déjà prit";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <?php include_once './include/head-include.php' ?>
    <title>Réserver un match</title>
</head>

<body>
    <header>
        <nav>
            <?php include './include/header-include.php'; ?>
        </nav>
    </header>
    <div id="backgroundreservation-form">
        <main>
            <h1 class="bk_font">Prepare your match !</h1>
            <p class="bk_font3">Choice your date and your hours it's <br>maximum 1hours of match !<br><br> So select ur favorite day <br>and good luck my brother !</p>
            <form action="reservation-form.php" method="POST">
                <label class="bk_font2" for="titre">Match</label>
                <select id="titre" name="titre" required>
                    <option value="1 vs 1">1 vs 1</option>
                    <option value="2 vs 2">2 vs 2</option>
                    <option value="3 vs 3">3 vs 3</option>
                    <option value="4 vs 4">4 vs 4</option>
                    <option value="5 vs 5">5 vs 5</option>
                </select>
                <br>
                <label class="bk_font2" for="equipe">Equipe</label>
                <input type="text" id="description" name="description" required>
                <br>
                <!-- Date de Debut -->
                <!-- ///////////// -->
                <label class="bk_font2" for="debut">Date Début</label>
                <?php
                if (isset($_GET["date_debut"])) {
                    $date_debut = $_GET["date_debut"];

                    if ($date_debut == 1) {
                        $date_select = date('Y-m-d', strtotime('monday this week'));
                    }
                    if ($date_debut == 2) {
                        $date_select = date('Y-m-d', strtotime('tuesday this week'));
                    }
                    if ($date_debut == 3) {
                        $date_select = date('Y-m-d', strtotime('wednesday this week'));
                    }
                    if ($date_debut == 4) {
                        $date_select = date('Y-m-d', strtotime('thursday this week'));
                    }
                    if ($date_debut == 5) {
                        $date_select = date('Y-m-d', strtotime('friday this week'));
                    }

                ?>
                    <input type="date" id="debut" name="debut_date" min="<?php echo date('Y-m-d') ?>" value="<?php echo $date_select; ?>" required>
                <?php
                } else {
                ?>
                    <input type="date" id="debut" name="debut_date" min="<?php echo date('Y-m-d') ?>" />
                <?php
                }
                ?>

                <!-- HEURE DEBUT -->
                <!-- /////////// -->
                <select id="debut" name="debut_heure" required>
                    <?php
                    if (isset($_GET["heure_debut"])) {
                        for ($heure_select = 8; $heure_select <= 19; $heure_select++) {
                            if ($heure_select < 10) {
                    ?>
                                <option value="<?php echo "0" . $heure_select . ":00"; ?>" <?php if ($heure_select == $_GET["heure_debut"]) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo "0" . $heure_select . ":00"; ?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?php echo $heure_select . ":00"; ?>" <?php if ($heure_select == $_GET["heure_debut"]) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $heure_select . ":00"; ?></option>
                            <?php
                            }
                        }
                    } else {
                        for ($heure_select = 8; $heure_select <= 19; $heure_select++) {
                            if ($heure_select < 10) {
                            ?>
                                <option value="<?php echo "0" . $heure_select . ":00"; ?>"><?php echo "0" . $heure_select . ":00"; ?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?php echo $heure_select . ":00"; ?>"><?php echo $heure_select . ":00"; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
                <br>
                <!-- Date et Heure de fin -->
                <!-- //////////////////// -->
                <label class="bk_font2">Date Fin</label>
                <?php
                if (isset($_GET["date_debut"])) {
                ?>
                    <input type="date" id="fin" name="fin_date" min="<?php echo date('Y-m-d') ?>" value="<?php echo $date_select; ?>" required>
                <?php
                } else {
                ?>
                    <input type="date" id="fin" name="fin_date" min="<?php echo date('Y-m-d') ?>" />
                <?php
                }
                ?>

                <!-- FIN HEURE -->
                <!-- ///////// -->
                <select id="fin" name="fin_heure" required>
                    <?php
                    if (isset($_GET["heure_debut"])) {
                        for ($heure_fin = 9; $heure_fin <= 20; $heure_fin++) {
                            if ($heure_fin < 10) {
                    ?>
                                <option value="<?php echo "0" . $heure_fin . ":00"; ?>" <?php if ($heure_fin == $_GET["heure_debut"] + 1) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo "0" . $heure_fin . ":00"; ?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?php echo $heure_fin . ":00"; ?>" <?php if ($heure_fin == $_GET["heure_debut"] + 1) {
                                                                                        echo "selected";
                                                                                    } ?>><?php echo $heure_fin . ":00"; ?></option>
                            <?php
                            }
                        }
                    } else {
                        for ($heure_fin = 9; $heure_fin <= 20; $heure_fin++) {
                            if ($heure_fin < 10) {
                            ?>
                                <option value="<?php echo "0" . $heure_fin . ":00"; ?>"><?php echo "0" . $heure_fin . ":00"; ?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?php echo $heure_fin . ":00"; ?>"><?php echo $heure_fin . ":00"; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
                <!-- /////////////// -->
                <br>
                <label class="bk_font">Good luck my brother !</label>
                <!-- BOUTON RESERVER -->
                <input type="submit" name="validresa" class="button" value="Réserver">

                <?php
                if (isset($msg_error)) {
                    echo "<p>" . $msg_error . "</p><br/>";
                }
                if (isset($msg_valid)) {
                    echo "<p>" . $msg_valid . "</p><br/>";
                }
                ?>
            </form>
        </main>
    </div>

    <footer><?php include_once("./include/footer.php"); ?></footer>

</body>

</html>