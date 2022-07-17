<?php
require_once("./pdo.php");
session_start();

//verification que j'ai un utilisateur authentifié
if (!isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

$sql = "SELECT * FROM autos WHERE autos_id = :autos_id";
$query = $pdo->prepare($sql);
$query->execute([
    ":autos_id" => $_SESSION["autos_id"]
]);
$result = $query->fetch(PDO::FETCH_ASSOC);

// on sauvegarde les modifications
if (isset($_POST["save"])) {
    echo "save ";

    //on recupere les variables
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $year = $_POST["year"];
    $mileage = $_POST["mileage"];

    $updateQuery = "UPDATE autos SET brand = :brand,model=:model,year=:year,mileage=:mileage WHERE autos_id = :autos_id";
    // on se protege contre les injection sql
    $query = $pdo->prepare($updateQuery);

    // on execute la requete, on affiche un message et on retourne sur la page app.php
    $query->execute([
        ":brand" => $brand,
        ":model" => $model,
        ":year" => $year,
        ":mileage" => $mileage,
        ":autos_id" => $_SESSION["autos_id"]
    ]);

    $_SESSION["success"] = "voiture modifiée avec succes";
    header("Location: add.php");
    return;
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit</title>
</head>

<body>
    <div class="container">
        <h1>Modifier une automobile</h1>
        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        ?>
        <form method="POST">
            <div>
                <label for="brand">Marque :</label>
                <input type="text" name="brand" id="brand" value="<?php echo $result['brand'] ?>">
            </div>
            <div>
                <label for="model">Modèle :</label>
                <input type="text" name="model" id="model" value="<?php echo $result['model'] ?>">
            </div>
            <div>
                <label for="year">Année :</label>
                <input type="text" name="year" id="year" value="<?php echo $result['year'] ?>">
            </div>
            <div>
                <label for="mileage">Kilométrage :</label>
                <input type="text" name="mileage" id="mileage" value="<?php echo $result['mileage'] ?>">
            </div>
            <button type="submit" name="save">Sauvegarder</button>
            <button type="submit" name="cancel">Annuler</button>
        </form>
    </div>



</body>

</html>