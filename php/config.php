<?php
// ============================================================
// Configuración de conexión a la base de datos PostgreSQL
// Ajustar los valores según el entorno local
// ============================================================

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'registro_productos');
define('DB_USER', 'postgres');
define('DB_PASS', 'admin');

function conectarDB(): PDO {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Usar prepared statements reales de PostgreSQL
    ]);

    return $pdo;
}
