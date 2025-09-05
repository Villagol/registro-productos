<?php
// Conexión a BD
$conn = pg_connect("host=localhost dbname=tienda user=postgres password=admin007");
if(!$conn){die("No se realizó la conexión a la BD");}

// Cargar datos (Bodegas y Monedas)
$bodegas = pg_query($conn, "SELECT id, nombre FROM inventario.bodega ORDER BY nombre ASC");
$monedas = pg_query($conn, "SELECT id, codigo FROM inventario.moneda ORDER BY codigo ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Productos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="container">
      <h1>Formulario de Producto</h1>
      <form id="formProducto" autocomplete="off">
        
        <div class="form-grid">
          <div class="form-row">
            <label for="codigo">Código</label>
            <input type="text" id="codigo" name="codigo">
          </div>
          <div class="form-row">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre">
          </div>

          <div class="form-row">
            <label for="bodega">Bodega</label>
            <select name="bodega" id="bodega">
              <option value=""></option>
              <?php while($row = pg_fetch_assoc($bodegas)): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>">
                  <?= htmlspecialchars($row['nombre']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-row">
            <label for="sucursal">Sucursal</label>
            <select id="sucursal" name="sucursal" disabled>
              <option value=""></option>
            </select>
          </div>

          <div class="form-row">
            <label for="moneda">Moneda</label>
            <select id="moneda" name="moneda">
              <option value=""></option>
              <?php while($row = pg_fetch_assoc($monedas)): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>">
                  <?= htmlspecialchars($row['codigo']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-row">
            <label for="precio">Precio</label>
            <input type="text" id="precio" name="precio">
          </div>
        </div>

        <fieldset>
          <legend>Material del Producto</legend>
          <label class="chk"><input type="checkbox" name="material[]" value="Plástico"> Plástico</label>
          <label class="chk"><input type="checkbox" name="material[]" value="Metal"> Metal</label>
          <label class="chk"><input type="checkbox" name="material[]" value="Madera"> Madera</label>
          <label class="chk"><input type="checkbox" name="material[]" value="Vidrio"> Vidrio</label>
          <label class="chk"><input type="checkbox" name="material[]" value="Textil"> Textil</label>
        </fieldset>

        <div class="form-row textarea-row">
          <label for="descripcion">Descripción</label>
          <textarea name="descripcion" id="descripcion" rows="4"></textarea>
        </div>

        <div class="actions">
          <button type="button" id="btnGuardar">Guardar Producto</button>
        </div>
        <div id="resultado" class="resultado" role="status" aria-live="polite"></div>
      </form>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
