<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h2>Bienvenue dans la base de données Automobiles</h2>

        <?php
        if (isset($_SESSION["success"])) {
            echo "<small style='color: green'>{$_SESSION["success"]}</small>";
            unset($_SESSION["success"]);
        }
        ?>

        <a href='./login.php'>Connectez-vous</a>
        <p>Essayer d'<a href='./add.php'>ajouter des données</a> sans se connecter</p>
    </div>
</body>
</html>