<?php
header('Content-Type: application/json; charset=utf-8');

$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
if ($codigo === '') {
  echo json_encode(['ok' => false, 'exists' => false, 'message' => 'Código vacío']);
  exit;
}

include "conexion.php";
if (!$conn) {
  echo json_encode(['ok' => false, 'exists' => false, 'message' => 'Error de conexión']);
  exit;
}

$check = pg_query_params($conn, "SELECT 1 FROM inventario.producto WHERE codigo=$1", [$codigo]);

$exists = ($check && pg_num_rows($check) > 0);

echo json_encode(['ok' => true, 'exists' => $exists]);
?>
