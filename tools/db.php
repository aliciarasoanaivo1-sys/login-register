<?php
function getDatabaseConnection() {
    // 1. On lit le fichier .env
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            putenv($line);
        }
    }

    // 2. On récupère les variables d'environnement
    $servername = getenv('DB_SERVER');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $database = getenv('DB_NAME');

    // 3. Connexion à la base de données
    $connection = new mysqli($servername, $username, $password, $database);

    if($connection->connect_error) {
        die("Error failed to connect to MySQL: " . $connection->connect_error);
    }

    return $connection;
}
?>