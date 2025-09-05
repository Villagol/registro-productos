-- Crear esquema
CREATE SCHEMA inventario;

-- Crear tablas y eliminar para evitar errores
DROP TABLE IF EXISTS inventario.producto CASCADE;
DROP TABLE IF EXISTS inventario.sucursal CASCADE;
DROP TABLE IF EXISTS inventario.bodega CASCADE;
DROP TABLE IF EXISTS inventario.moneda CASCADE;

CREATE TABLE inventario.bodega (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE inventario.sucursal (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT REFERENCES inventario.bodega(id)
);

CREATE TABLE inventario.moneda (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL
);

CREATE TABLE inventario.producto (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(15) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT REFERENCES inventario.bodega(id),
    sucursal_id INT REFERENCES inventario.sucursal(id),
    moneda_id INT REFERENCES inventario.moneda(id),
    precio NUMERIC(10,2) NOT NULL,
    material VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL
);

-- Insertar datos
INSERT INTO inventario.bodega (nombre) VALUES
('Bodega 1'),
('Bodega 2'),
('Bodega 3');

INSERT INTO inventario.sucursal (nombre, bodega_id) VALUES
('Sucursal 1A', 1),
('Sucursal 1B', 1),
('Sucursal 1C', 1),

('Sucursal 2A', 2),
('Sucursal 2B', 2),
('Sucursal 2C', 2),

('Sucursal 3A', 3),
('Sucursal 3B', 3),
('Sucursal 3C', 3);

INSERT INTO inventario.moneda (codigo) VALUES
('CLP'),
('USD'),
('EUR'),
('GBP'),
('JPY');

-- Ver Productos
SELECT * FROM inventario.producto;
