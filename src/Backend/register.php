<?php
// src/Backend/register.php
session_start();
require_once "config.php";

// Solo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo_usuario = $_POST['tipo_usuario'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidoP = $_POST['apellidoP'] ?? '';
    $apellidoM = $_POST['apellidoM'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Campos de monitor
    $numeroControl = $_POST['numeroControl'] ?? null;
    $carrera = $_POST['carrera'] ?? null;
    $semestre = $_POST['semestre'] ?? null;

    if (!$tipo_usuario || !$nombre || !$apellidoP || !$apellidoM || !$usuario || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        exit();
    }

    // Revisar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE usuario = :usuario LIMIT 1");
    $stmt->execute(['usuario' => $usuario]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'El usuario ya existe']);
        exit();
    }

    // Hashear la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en la base
    $stmt = $pdo->prepare("INSERT INTO usuarios (tipo_usuario, nombre, apellido_paterno, apellido_materno, usuario, password, numero_control, carrera, semestre) 
                           VALUES (:tipo_usuario, :nombre, :apellidoP, :apellidoM, :usuario, :password, :numero_control, :carrera, :semestre)");

    $stmt->execute([
        'tipo_usuario' => strtoupper($tipo_usuario),
        'nombre' => $nombre,
        'apellidoP' => $apellidoP,
        'apellidoM' => $apellidoM,
        'usuario' => $usuario,
        'password' => $passwordHash,
        'numero_control' => $numeroControl,
        'carrera' => $carrera,
        'semestre' => $semestre
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Usuario creado correctamente']);
    exit();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
