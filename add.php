<?php
require_once("./pdo.php");
session_start();

//verification que j'ai un utilisateur authentifié
if (!isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

// recuperer toute les informations qui sont reliées a cet user_id
$sql = "SELECT * FROM `autos` ";
$query = $pdo->prepare($sql);
$query->execute();

//fetchAll pour bien récuperer tous les resulats dans la base et les mettre dans la variable $result.
$result = $query->fetchAll(PDO::FETCH_ASSOC);

//modifier une voiture 
if (isset($_POST["edit"])) {
    echo "edit ";
    // on envoie à la page edit.php la variable task_id, en la mettant dans la session ($session)
    $_SESSION["autos_id"] = $_POST["autos_id"];
    header("Location: edit.php");
    return;
}

if (isset($_POST["delete"])) {
    echo "delete ";
    $deleteQuery = "DELETE FROM autos WHERE autos_id = :autos_id";
    $query = $pdo->prepare($deleteQuery);
    $query->execute([
        ":autos_id" => $_POST["autos_id"]
    ]);
    $_SESSION["success"] = "voiture supprimée avec succes";
    header("Location: add.php");
    return;
}


// je peux ajouter une voiture(marque, model, année, kilometrage)
//des que l'utilisateur appuie sur Ajouter 

if (isset($_POST["add"])) {
    echo "add ";
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $year = $_POST["year"];
    $mileage = $_POST["mileage"];

    // verifier que tous les champs soit remplis
    // verifier que le kilometrage et la dates soit des chifres
    if ($brand && $model && $year && $mileage) {


        // verifier si l'année et mileage c'est bien des nombres

        if (is_numeric($year) && is_numeric($mileage)) {
            $addQuery = "INSERT INTO autos(brand, model, year, mileage) VALUES (:brand,:model,:year,:mileage)";
            
            // on se protege contre les injection sql
            $query = $pdo->prepare($addQuery);

            $query->execute([
                ":brand" => $brand,
                ":model" => $model,
                ":year" => $year,
                ":mileage" => $mileage,
            ]);

            $_SESSION["success"] = "voiture ajoutée avec succes";
            header("Location: add.php");
            return;



        } else {
            $_SESSION["error"] = "l'année et le kilometrage doivent etre des nombres";
            header("location: add.php");

            return;
        }
    } else {
        $_SESSION["error"] = "Tous les champs sont requis";
        header("location: add.php");

        return;
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add</title>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo "<small style='color: green'>{$_SESSION["success"]}</small>";
            unset($_SESSION["success"]);
        }

        ?>
        <h1>Bienvenue sur la base de données Automobiles</h1>
        <div class="liste-voiture">
            <table border='1'>
                <tr>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Kilométrage</th>
                    <th>Action</th>
                </tr>

                <?php

                foreach ($result as $element) {
                    $_SESSION["autos_id"] = $element['autos_id'];

                ?>
                    <div class="rows">
                        <form method="POST">
                            <tr>
                                <input type="hidden" name="autos_id" value="<?php echo $element['autos_id'] ?>">
                                <td>
                                    <input readonly type="text" name="brand" value="<?php echo $element['brand'] ?>">

                                </td>
                                <td>
                                    <input readonly type="text" name="model" value="<?php echo $element['model'] ?>">
                                </td>
                                <td>
                                    <input readonly type="text" name="year" value="<?php echo $element['year'] ?>">

                                </td>
                                <td>
                                    <input readonly type="text" name="mileage" value="<?php echo $element['mileage'] ?>">
                                </td>
                                <td>

                                    <button class="btn btn-outline-secondary btn-sm" type="submit" name="edit">edit</button>
                                    <button class="btn btn-outline-danger btn-sm" type="submit" name="delete">Delete</button>
                                </td>
                            </tr>
                        </form>
                    </div>

                <?php
                }
                ?>
            </table>
        </div>

        <div class="addCar">
            <p><a href='./add.php'>Ajouter Une Nouvelle Entrée</a></p>
            <table>
                <tr>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Kilométrage</th>
                    <th>Action</th>
                </tr>
                <div class="rows1">
                    <form method="post">
                        <tr>
                            <td>
                                <input type="text" name="brand">
                            </td>
                            <td>
                                <input type="text" name="model">
                            </td>
                            <td>
                                <input type="text" name="year">
                            </td>
                            <td>
                                <input type="text" name="mileage">
                            </td>
                            <td>
                                <button class="btn" type="submit" name="add">Ajouter</button>
                            </td>
                        </tr>
                    </form>

                </div>
            </table>

        </div>
        <a href='./logout.php'>Se Déconnecter</a>
    </div>

</body>

</html>