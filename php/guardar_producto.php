<?php
// Endpoint: recibe los datos del formulario por POST y guarda el producto
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

// Sanitizar y obtener campos del POST
$codigo      = trim($_POST['codigo'] ?? '');
$nombre      = trim($_POST['nombre'] ?? '');
$bodega_id   = filter_input(INPUT_POST, 'bodega_id', FILTER_VALIDATE_INT);
$sucursal_id = filter_input(INPUT_POST, 'sucursal_id', FILTER_VALIDATE_INT);
$moneda_id   = filter_input(INPUT_POST, 'moneda_id', FILTER_VALIDATE_INT);
$precio      = trim($_POST['precio'] ?? '');
$materiales  = $_POST['materiales'] ?? [];
$descripcion = trim($_POST['descripcion'] ?? '');

// Validaciones del lado del servidor (segunda línea de defensa)
if (!$codigo || !$nombre || !$bodega_id || !$sucursal_id || !$moneda_id || !$precio || !$descripcion) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios.']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9]{5,15}$/', $codigo)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de código inválido.']);
    exit;
}

if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio) || floatval($precio) <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de precio inválido.']);
    exit;
}

if (count($materiales) < 2) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requieren al menos dos materiales.']);
    exit;
}

try {
    $pdo = conectarDB();

    // Verificar unicidad del código en la base de datos
    $check = $pdo->prepare("SELECT id FROM productos WHERE codigo = :codigo");
    $check->execute([':codigo' => $codigo]);
    if ($check->fetch()) {
        echo json_encode(['error' => 'El código del producto ya está registrado.']);
        exit;
    }

    // Convertir el array de materiales a formato de array de PostgreSQL: {"val1","val2"}
    $materiales_limpios = array_map('trim', $materiales);
    $pg_array = '{' . implode(',', array_map(fn($m) => '"' . addslashes($m) . '"', $materiales_limpios)) . '}';

    $stmt = $pdo->prepare("
        INSERT INTO productos (codigo, nombre, bodega_id, sucursal_id, moneda_id, precio, materiales, descripcion)
        VALUES (:codigo, :nombre, :bodega_id, :sucursal_id, :moneda_id, :precio, :materiales, :descripcion)
    ");

    $stmt->execute([
        ':codigo'      => $codigo,
        ':nombre'      => $nombre,
        ':bodega_id'   => $bodega_id,
        ':sucursal_id' => $sucursal_id,
        ':moneda_id'   => $moneda_id,
        ':precio'      => $precio,
        ':materiales'  => $pg_array,
        ':descripcion' => $descripcion,
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Producto registrado correctamente.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno al guardar el producto.']);
}
