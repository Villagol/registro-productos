<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos</title>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h1>Formulario de Producto</h1>

    <form id="form-producto" novalidate>

        <!-- novalidate: deshabilita validación nativa HTML para usar solo JavaScript -->

        <!-- Fila 1: Código y Nombre lado a lado -->
        <div class="fila-doble">
            <div class="campo">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" placeholder="">
            </div>
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="">
            </div>
        </div>

        <!-- Fila 2: Bodega y Sucursal lado a lado -->
        <div class="fila-doble">
            <div class="campo">
                <label for="bodega">Bodega</label>
                <select id="bodega" name="bodega_id">
                    <option value="">-- Seleccione una bodega --</option>
                </select>
            </div>
            <div class="campo">
                <label for="sucursal">Sucursal</label>
                <select id="sucursal" name="sucursal_id">
                    <option value="">-- Seleccione una sucursal --</option>
                </select>
            </div>
        </div>

        <!-- Fila 3: Moneda y Precio lado a lado -->
        <div class="fila-doble">
            <div class="campo">
                <label for="moneda">Moneda</label>
                <select id="moneda" name="moneda_id">
                    <option value="">-- Seleccione una moneda --</option>
                </select>
            </div>
            <div class="campo">
                <label for="precio">Precio</label>
                <input type="text" id="precio" name="precio" placeholder="">
            </div>
        </div>

        <!-- Material: fila completa con checkboxes -->
        <div class="campo">
            <label>Material del Producto</label>
            <div class="grupo-checkboxes">
                <label><input type="checkbox" name="materiales[]" value="Plástico"> Plástico</label>
                <label><input type="checkbox" name="materiales[]" value="Metal"> Metal</label>
                <label><input type="checkbox" name="materiales[]" value="Madera"> Madera</label>
                <label><input type="checkbox" name="materiales[]" value="Vidrio"> Vidrio</label>
                <label><input type="checkbox" name="materiales[]" value="Textil"> Textil</label>
            </div>
        </div>

        <!-- Descripción: fila completa -->
        <div class="campo">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder=""></textarea>
        </div>

        <!-- Botón centrado y no ancho completo -->
        <div class="btn-wrapper">
            <button type="submit" class="btn-guardar">Guardar Producto</button>
        </div>

    </form>

    <!-- Mensaje de éxito o error tras el envío -->
    <div id="mensaje" class="mensaje"></div>

</div>

<script src="/js/formulario.js"></script>
</body>
</html>