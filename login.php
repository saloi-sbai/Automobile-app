<?php
require_once("./pdo.php");
session_start();
$name = $_POST["email"] ?? "";
$password = $_POST["pass"] ?? "";
$salt = 'ZDCngr*&22/';

if (isset($_POST["login"])) {
    unset($_SESSION["user_id"]);
    if ($name && $password) {
        $password = hash('md5', $salt . htmlentities($password));

        //on recupere le password hashé pour le mettre dans la table users.
        echo "password :" . $password;

        $sql = "SELECT user_id, name FROM users WHERE name = :name AND password = :password";
        $query = $pdo->prepare($sql);
        $query->execute([
            ":name" => $name,
            ":password" => $password
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION["success"] = "Vous etes connecté";
            $_SESSION["user_id"] = $result["user_id"];
            $_SESSION["name"] = $result["name"];
            header("Location: add.php");
            return;
        } else if ($password != $result["password"]) {
            //si le mot de passe tapé par l'utilisateur ne correspond pas a celui enregistré dans ma BDD 
            $_SESSION["error"] = "Le mot de passe est incorrect";
            header("Location: login.php");
            return;
        }
    } else {
        $_SESSION["error"] = "Le nom d'utilisateur et le mot de passe sont requis";
        header("Location: login.php");
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
    <title>Login</title>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        ?>

        <h2>Se Connecter</h2>
        <form method="POST" action="./login.php">
            <div>
                <label for="email">Nom d'Utilisateur</label>
                <input type="text" name="email" id="email">
            </div>
            <div>
                <label for="password">Mot de Passe</label>
                <input type="password" name="pass" id="password">
            </div>
            <!--donner un nom au bouton pour declencher l'action -->
            <button type="submit" name="login">Se Connecter</button>
            <a href="./index.php">Annuler</a>
        </form>
    </div>

</body>

</html>