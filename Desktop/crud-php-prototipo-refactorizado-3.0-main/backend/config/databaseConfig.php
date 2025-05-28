<?php
require_once __DIR__ . '/loadEnv.php'; // Cargar el lector de .env
loadEnv(__DIR__ . '/../.env'); // Ruta relativa al archivo .env

// Obtener variables de entorno
$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

// Conexión MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Conexión MySQL fallida: " . $conn->connect_error]));
}

//  establecer charset
$conn->set_charset("utf8");
?>
