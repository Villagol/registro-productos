<?php
// Endpoint: verifica si un código ya existe en la base de datos
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

$codigo = trim($_POST['codigo'] ?? '');

if (!$codigo) {
    echo json_encode(['existe' => false]);
    exit;
}

try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM productos WHERE codigo = :codigo");
    $stmt->execute([':codigo' => $codigo]);
    echo json_encode(['existe' => (bool) $stmt->fetch()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al verificar el código.']);
}