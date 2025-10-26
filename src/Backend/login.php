<?php
// backend/login.php
session_start();
require_once "config.php";

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

    if (!$usuario || !$password || !$tipo_usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        exit();
    }

    // Preparar consulta
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND tipo_usuario = :tipo_usuario LIMIT 1");
    $stmt->execute(['usuario' => $usuario, 'tipo_usuario' => strtoupper($tipo_usuario)]);
    $user = $stmt->fetch();

    if ($user) {
        // Verificar contraseña (asumiendo hash bcrypt)
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id_usuario'];
            $_SESSION['usuario_nombre'] = $user['nombre'] . ' ' . $user['apellido_paterno'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

            echo json_encode([
                'status' => 'success',
                'redirect' => ($tipo_usuario === 'oficina') ? '../frontend/Oficina.html' : '../frontend/Monitor.html'
            ]);
            exit();
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Usuario o contraseña incorrectos']);
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
