# Registro de Productos

Aplicación web para registrar productos con validaciones en frontend y backend.
Tecnologías: HTML, CSS, PHP, JavaScript(AJAX) y PostgreSQL.

## Requisitos
- PHP: 8.0.30
- PostgreSQL: 17.6
- Apache (XAMPP)

## Instalación
1. Clonar el proyecto en la carpeta pública de tu servidor (en XAMPP es `htdocs`):
   git clone https://github.com/Villagol/registro-productos.git

2. Crear la base de datos `tienda` en PostgreSQL.

3. Importar el SQL de `/sql/productos.sql` (pgAdmin → Query Tool → ejecutar).

4. Configurar credenciales de BD en:
   - `index.php`
   - `guardar.php`
   - `sucursales.php`
   - `check_codigo.php`

   Ejemplo de cadena:
   pg_connect("host=localhost dbname=tienda user=postgres password=TU_CONTRASEÑA");

5. Iniciar Apache (XAMPP).

6. Abrir en el navegador:
   http://localhost/registro-productos/index.php

## Estructura
/registro-productos
├─ index.php
├─ guardar.php
├─ sucursales.php
├─ check_codigo.php
├─ css/
│  └─ estilos.css
├─ js/
│  └─ app.js
└─ sql/
   └─ productos.sql
