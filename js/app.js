const $ = (s) => document.querySelector(s);
const $$ = (s) => document.querySelectorAll(s);

let lastAlertedCode = null;

async function checkCodigoExiste(codigo) {
  if (!codigo) return false;
  const r = await fetch(`check_codigo.php?codigo=${encodeURIComponent(codigo)}`);
  const json = await r.json();
  return !!(json.ok && json.exists);
}

$("#precio").addEventListener("input", () => {
  const val = $("#precio").value;
  if (val.includes(",")) {
    $("#precio").value = val.replace(/,/g, ".");
  }
});

$("#bodega").addEventListener("change", async () => {
  const bodegaId = $("#bodega").value.trim();
  const $sucursal = $("#sucursal");
  $sucursal.innerHTML = '<option value=""></option>';
  $sucursal.disabled = true;
  if (bodegaId === "") return;
  try {
    const r = await fetch(`sucursales.php?bodega_id=${encodeURIComponent(bodegaId)}`);
    const json = await r.json();
    if (!json.ok) return;
    json.data.forEach(it => {
      const opt = document.createElement("option");
      opt.value = it.id;
      opt.textContent = it.nombre;
      $sucursal.appendChild(opt);
    });
    if (json.data.length > 0) $sucursal.disabled = false;
  } catch {}
});

$("#codigo").addEventListener("input", () => { lastAlertedCode = null; });

$("#codigo").addEventListener("blur", async () => {
  const codigo = $("#codigo").value.trim();
  if (codigo === "") return;
  if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,15}$/.test(codigo)) return;
  const existe = await checkCodigoExiste(codigo);
  if (existe && lastAlertedCode !== codigo) {
    lastAlertedCode = codigo;
    alert("El código del producto ya está registrado.");
    $("#codigo").focus();
    $("#codigo").select();
  }
});

$("#btnGuardar").addEventListener("click", async () => {
  const codigo = $("#codigo").value.trim();
  const nombre = $("#nombre").value.trim();
  const bodega = $("#bodega").value.trim();
  const sucursal = $("#sucursal").value.trim();
  const moneda = $("#moneda").value.trim();
  const precio = $("#precio").value.replace(/,/g, ".").trim(); // normaliza por si acaso
  const descripcion = $("#descripcion").value.trim();
  const materiales = Array.from($$('input[name="material[]"]:checked')).map(el => el.value);

  if (codigo === "") { alert("El código del producto no puede estar en blanco"); return; }
  if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,15}$/.test(codigo)) {
    const len = codigo.length;
    if (len < 5 || len > 15) { alert("El código del producto debe tener entre 5 y 15 caracteres"); return; }
    alert("El código del producto debe contener letras y números"); return;
  }

  const existe = await checkCodigoExiste(codigo);
  if (existe) {
    if (lastAlertedCode !== codigo) {
      lastAlertedCode = codigo;
      alert("El código del producto ya está registrado.");
    }
    $("#codigo").focus();
    $("#codigo").select();
    return;
  }

  if (nombre === "") { alert("El nombre del producto no puede estar en blanco"); return; }
  if (nombre.length < 2 || nombre.length > 50) { alert("El nombre del producto debe tener entre 2 y 50 caracteres"); return; }
  if (bodega === "") { alert("Debe seleccionar una bodega"); return; }
  if (sucursal === "") { alert("Debe seleccionar una sucursal para la bodega seleccionada"); return; }
  if (moneda === "") { alert("Debe seleccionar una moneda para el producto"); return; }
  if (precio === "") { alert("El precio del producto no puede estar en blanco"); return; }
  if (!/^(?:\d+)(?:\.\d{1,2})?$/.test(precio)) { alert("El precio del producto debe ser un número positivo con hasta dos decimales"); return; }
  if (materiales.length < 2) { alert("Debe seleccionar al menos dos materiales para el producto"); return; }
  if (descripcion === "") { alert("La descripción del producto no puede estar en blanco"); return; }
  if (descripcion.length < 10 || descripcion.length > 1000) { alert("La descripción del producto debe tener entre 10 y 1000 caracteres"); return; }

  const fd = new FormData();
  fd.append("codigo", codigo);
  fd.append("nombre", nombre);
  fd.append("bodega", bodega);
  fd.append("sucursal", sucursal);
  fd.append("moneda", moneda);
  fd.append("precio", precio);
  materiales.forEach(m => fd.append("material[]", m));
  fd.append("descripcion", descripcion);

  fetch("guardar.php", { method: "POST", body: fd })
  .then(r => r.json())
  .then(res => {
    if (!res.ok) {
      alert(res.message || "Error al guardar el producto");
      return;
    }
    alert("Producto guardado con éxito");
    $("#formProducto").reset();
    $("#sucursal").disabled = true;
    lastAlertedCode = null;
  })
  .catch(() => alert("Error de red al intentar guardar"));
});
