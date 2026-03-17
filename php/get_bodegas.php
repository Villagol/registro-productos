<?php
// Endpoint: devuelve todas las bodegas en formato JSON
header('Content-Type: application/json');
require_once 'config.php';

try {
    $pdo = conectarDB();
    $stmt = $pdo->query("SELECT id, nombre FROM bodegas ORDER BY nombre");
    echo json_encode($stmt->fetchAll());
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las bodegas.']);
}
