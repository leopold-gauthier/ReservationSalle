<?php
session_start();
require "./include/config.php";

if ($_SESSION['login'] != 'admin') {
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("./include/head-include.php"); ?>
    <title>Admin</title>
</head>

<body>
    <header>
        <nav>
            <?php require './include/header-include.php' ?>
        </nav>
    </header>

    <main>
        <?php
        $request = $bdd->query('SELECT id,login FROM utilisateurs');
        $result = $request->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table>
            <h1>All Users</h1>
            <thead>
                <tr>
                    <?php
                    foreach ($result[0] as $key => $value) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < sizeof($result); $i++) : ?>
                    <tr>
                        <td><?= $result[$i]['id'] ?></td>
                        <td><?= $result[$i]['login'] ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <style>
            main {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 2% 0;
            }

            table {
                margin: 2% 0 0;
                border-collapse: collapse;
                width: 60%;
            }

            th,
            td {
                padding: 0.5em;
                border: 1px solid;
                text-align: center;
            }

            tr:hover {
                background-color: rgba(255, 255, 255, 0.3);
            }
        </style>
    </main>

    <footer><a href="https://github.com/Dylan-olivro"><i class="fa-brands fa-github"></i></a></footer>
</body>

</html>