<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "automobile_db";

$dsn = "mysql:host=$dbHost;dbname=$dbName";

try {
    $pdo = new PDO(
        $dsn,
        $dbUser,
        $dbPassword
    );
} catch (PDOException $e) {
    echo "Message d'erreur: " . $e->getMessage();
}
