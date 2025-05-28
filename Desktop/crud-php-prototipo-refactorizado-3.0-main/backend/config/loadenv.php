<?php
// loadEnv.php - Carga manual de variables de entorno desde un archivo .env

function loadEnv($path) {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar líneas vacías y comentarios
        if (strpos(trim($line), '#') === 0) continue;

        // Dividir clave y valor
        list($key, $value) = explode('=', $line, 2);

        // Limpiar espacios y comillas
        $key = trim($key);
        $value = trim($value, "'\"");

        // Cargar en variables de entorno
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
