--Eliminar tablas para evitar errores
DROP TABLE IF EXISTS producto CASCADE;
DROP TABLE IF EXISTS sucursal CASCADE;
DROP TABLE IF EXISTS bodega CASCADE;
DROP TABLE IF EXISTS moneda CASCADE;
--Crear tablas
CREATE TABLE bodega (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE sucursal (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT REFERENCES bodega(id)
);

CREATE TABLE moneda (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL
);

CREATE TABLE producto (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(15) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT REFERENCES bodega(id),
    sucursal_id INT REFERENCES sucursal(id),
    moneda_id INT REFERENCES moneda(id),
    precio NUMERIC(10,2) NOT NULL,
    material VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL
);

-- Insertar datos
INSERT INTO bodega (nombre) VALUES
('Bodega 1'),
('Bodega 2'),
('Bodega 3');

INSERT INTO sucursal (nombre, bodega_id) VALUES
('Sucursal 1A', 1),
('Sucursal 1B', 1),
('Sucursal 1C', 1),

('Sucursal 2A', 2),
('Sucursal 2B', 2),
('Sucursal 2C', 2),

('Sucursal 3A', 3),
('Sucursal 3B', 3),
('Sucursal 3C', 3);

INSERT INTO moneda (codigo) VALUES
('CLP'),
('USD'),
('EUR'),
('GBP'),
('JPY');
--Ver Productos
SELECT * FROM producto