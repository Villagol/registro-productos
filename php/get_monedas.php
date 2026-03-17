<?php
// Endpoint: devuelve todas las monedas disponibles
header('Content-Type: application/json');
require_once 'config.php';

try {
    $pdo = conectarDB();
    $stmt = $pdo->query("SELECT id, codigo, nombre FROM monedas ORDER BY nombre");
    echo json_encode($stmt->fetchAll());
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las monedas.']);
}
