<?php
header('Content-Type: application/json; charset=utf-8');

function param($key) { return isset($_POST[$key]) ? $_POST[$key] : null; }

$codigo = param('codigo');
$nombre = param('nombre');
$bodega = param('bodega');
$sucursal = param('sucursal');
$moneda = param('moneda');
$precio = param('precio');
$material = isset($_POST['material']) ? $_POST['material'] : [];
$descripcion = param('descripcion');

// Conexión con BD
include "conexion.php";
if (!$conn) {
  echo json_encode(['ok' => false, 'message' => 'No se pudo conectar a la base de datos']);
  exit;
}

$check = pg_query_params($conn, "SELECT 1 FROM inventario.producto WHERE codigo=$1", [$codigo]);
if ($check && pg_num_rows($check) > 0) {
  echo json_encode(['ok' => false, 'message' => 'El código del producto ya está registrado']);
  exit;
}

$material_str = implode(", ", $material);
$sql = "INSERT INTO inventario.producto (codigo, nombre, bodega_id, sucursal_id, moneda_id, precio, material, descripcion)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8)";
$params = [$codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $material_str, $descripcion];
$res = pg_query_params($conn, $sql, $params);

if ($res) {
  echo json_encode(['ok' => true]);
} else {
  echo json_encode(['ok' => false, 'message' => 'No se pudo guardar el producto']);
}
?>
