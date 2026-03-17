<?php
// Endpoint: devuelve las sucursales que pertenecen a una bodega específica
header('Content-Type: application/json');
require_once 'config.php';

$bodega_id = filter_input(INPUT_GET, 'bodega_id', FILTER_VALIDATE_INT);

if (!$bodega_id) {
    http_response_code(400);
    echo json_encode(['error' => 'bodega_id inválido.']);
    exit;
}

try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id, nombre FROM sucursales WHERE bodega_id = :bodega_id ORDER BY nombre");
    $stmt->execute([':bodega_id' => $bodega_id]);
    echo json_encode($stmt->fetchAll());
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las sucursales.']);
}
