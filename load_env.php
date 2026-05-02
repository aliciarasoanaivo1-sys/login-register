<?php
// Ce code lit le fichier .env et mémorise les valeurs
function loadEnv() {
    $path = __DIR__ . '/../.env';
    if (file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            putenv($line);
        }
    }
}
?>