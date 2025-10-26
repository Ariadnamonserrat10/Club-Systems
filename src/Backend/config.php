<?php
// backend/config.php

$host = "localhost";       // servidor MySQL
$db   = "sistema_clubs";   // nombre de la base
$user = "root";            // usuario de MySQL
$pass = "";                // contraseña (si XAMPP no tiene contraseña, deja vacío)
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores con excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // resultados como array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // usar prepared statements reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
