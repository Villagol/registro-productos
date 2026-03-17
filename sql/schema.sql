-- ============================================================
-- Schema del Sistema de Registro de Productos
-- Base de datos: PostgreSQL
-- ============================================================
BEGIN;
--COMMIT;
--ROLLBACK;

-- Tabla de bodegas
CREATE TABLE bodegas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de sucursales (cada sucursal pertenece a una bodega)
CREATE TABLE sucursales (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    bodega_id INT NOT NULL REFERENCES bodegas(id) ON DELETE CASCADE
);

-- Tabla de monedas
CREATE TABLE monedas (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla principal de productos
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT NOT NULL REFERENCES bodegas(id),
    sucursal_id INT NOT NULL REFERENCES sucursales(id),
    moneda_id INT NOT NULL REFERENCES monedas(id),
    precio NUMERIC(12, 2) NOT NULL CHECK (precio > 0),
    materiales TEXT[] NOT NULL,  -- Array con los materiales seleccionados
    descripcion TEXT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Datos de ejemplo
-- ============================================================

INSERT INTO bodegas (nombre) VALUES
    ('Bodega Central'),
    ('Bodega Norte'),
    ('Bodega Sur');

INSERT INTO sucursales (nombre, bodega_id) VALUES
    ('Sucursal A', 1),
    ('Sucursal B', 1),
    ('Sucursal C', 2),
    ('Sucursal D', 3);

INSERT INTO monedas (codigo, nombre) VALUES
    ('CLP', 'Peso Chileno'),
    ('USD', 'Dólar Americano'),
    ('EUR', 'Euro');
