<?php
header('Content-Type: application/json; charset=utf-8');
require "conexion.php";

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

switch ($tipo) {
  case "bodegas":
    $res = pg_query_params($conn, "SELECT id, nombre FROM inventario.bodega ORDER BY nombre ASC", []);
    $data = pg_fetch_all($res) ?: [];
    echo json_encode(['ok' => true, 'data' => $data]);
    break;

  case "monedas":
    $res = pg_query_params($conn, "SELECT id, codigo FROM inventario.moneda ORDER BY codigo ASC", []);
    $data = pg_fetch_all($res) ?: [];
    echo json_encode(['ok' => true, 'data' => $data]);
    break;

  case "sucursales":
    $bodegaId = isset($_GET['bodega_id']) ? intval($_GET['bodega_id']) : 0;
    if ($bodegaId <= 0) { echo json_encode(['ok' => false, 'message' => 'Parámetro inválido']); break; }
    $res = pg_query_params($conn, "SELECT id, nombre FROM inventario.sucursal WHERE bodega_id = $1 ORDER BY nombre ASC", [$bodegaId]);
    $data = pg_fetch_all($res) ?: [];
    echo json_encode(['ok' => true, 'data' => $data]);
    break;

  default:
    echo json_encode(['ok' => false, 'message' => 'Tipo no válido']);
}
