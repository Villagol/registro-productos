<?php
header('Content-Type: application/json; charset=utf-8');

$bodegaId = isset($_GET['bodega_id']) ? trim($_GET['bodega_id']) : '';
if ($bodegaId === '' || !ctype_digit($bodegaId)) {
  echo json_encode(['ok' => false, 'data' => [], 'message' => 'Parámetro inválido']);
  exit;
}

$conn = pg_connect("host=localhost dbname=tienda user=postgres password=admin007");
if (!$conn) {
  echo json_encode(['ok' => false, 'data' => [], 'message' => 'Error de conexión']);
  exit;
}


$sql = "SELECT id, nombre FROM sucursal WHERE bodega_id = $1 ORDER BY nombre ASC";
$res = pg_query_params($conn, $sql, [$bodegaId]);


$sql = "SELECT id, nombre FROM sucursal WHERE bodega_id = $1 ORDER BY nombre ASC";
$res = pg_query_params($conn, $sql, [$bodegaId]);

$data = [];
if ($res) {
  while ($row = pg_fetch_assoc($res)) { $data[] = $row; }
}

echo json_encode(['ok' => true, 'data' => $data]);
?>