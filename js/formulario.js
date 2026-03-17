// ============================================================
// Lógica del formulario: validaciones y envío por AJAX
// Toda la validación se realiza con JavaScript nativo (sin jQuery)
// ============================================================

// -------- Carga inicial --------

document.addEventListener('DOMContentLoaded', function () {
    // Limpiar formulario al cargar/recargar la página
    document.getElementById('form-producto').reset();
    document.getElementById('sucursal').innerHTML = '<option value="">-- Seleccione una sucursal --</option>';

    cargarBodegas();
    cargarMonedas();

    document.getElementById('bodega').addEventListener('change', function () {
        cargarSucursales(this.value);
    });

    // Verificar unicidad del código al salir del campo
    document.getElementById('codigo').addEventListener('blur', function () {
        verificarCodigo();
    });

    document.getElementById('form-producto').addEventListener('submit', function (e) {
        e.preventDefault();
        if (validarFormulario()) {
            enviarFormulario();
        }
    });
});

// -------- Carga de selects desde la base de datos --------

function cargarBodegas() {
    fetch('/php/get_bodegas.php')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('bodega');
            data.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.id;
                opt.textContent = b.nombre;
                select.appendChild(opt);
            });
        });
}

function cargarSucursales(bodegaId) {
    const select = document.getElementById('sucursal');
    select.innerHTML = '<option value="">Cargando...</option>';
    select.disabled = true;

    if (!bodegaId) {
        select.innerHTML = '<option value="">-- Seleccione una sucursal --</option>';
        select.disabled = false;
        return;
    }

    fetch('/php/get_sucursales.php?bodega_id=' + bodegaId)
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '<option value="">-- Seleccione una sucursal --</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.nombre;
                select.appendChild(opt);
            });
            select.disabled = false;
        });
}

function cargarMonedas() {
    fetch('/php/get_monedas.php')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('moneda');
            data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.codigo + ' - ' + m.nombre;
                select.appendChild(opt);
            });
        });
}

// -------- Validaciones (según especificación del enunciado) --------

function validarFormulario() {
    const codigo      = document.getElementById('codigo').value.trim();
    const nombre      = document.getElementById('nombre').value.trim();
    const bodega      = document.getElementById('bodega').value;
    const sucursal    = document.getElementById('sucursal').value;
    const moneda      = document.getElementById('moneda').value;
    const precio      = document.getElementById('precio').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const materiales  = document.querySelectorAll('input[name="materiales[]"]:checked');

    // Código del producto
    if (codigo === '') {
        alert('El código del producto no puede estar en blanco.');
        return false;
    }
    // Regex: solo letras y números, debe tener al menos una letra y un número
    if (!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/.test(codigo)) {
        alert('El código del producto debe contener letras y números');
        return false;
    }
    if (codigo.length < 5 || codigo.length > 15) {
        alert('El código del producto debe tener entre 5 y 15 caracteres.');
        return false;
    }

    // Nombre del producto
    if (nombre === '') {
        alert('El nombre del producto no puede estar en blanco.');
        return false;
    }
    if (nombre.length < 2 || nombre.length > 50) {
        alert('El nombre del producto debe tener entre 2 y 50 caracteres.');
        return false;
    }

    // Bodega
    if (!bodega) {
        alert('Debe seleccionar una bodega.');
        return false;
    }

    // Sucursal
    if (!sucursal) {
        alert('Debe seleccionar una sucursal para la bodega seleccionada.');
        return false;
    }

    // Moneda
    if (!moneda) {
        alert('Debe seleccionar una moneda para el producto.');
        return false;
    }

    // Precio: número positivo con hasta dos decimales
    if (precio === '') {
        alert('El precio del producto no puede estar en blanco.');
        return false;
    }
    // Normalizar coma a punto para aceptar ambos formatos (ej: 19,99 → 19.99)
    const precioNormalizado = precio.replace(',', '.');
    document.getElementById('precio').value = precioNormalizado;
    if (!/^\d+(\.\d{1,2})?$/.test(precioNormalizado) || parseFloat(precioNormalizado) <= 0) {
        alert('El precio del producto debe ser un número positivo con hasta dos decimales.');
        return false;
    }

    // Materiales: mínimo 2 seleccionados
    if (materiales.length < 2) {
        alert('Debe seleccionar al menos dos materiales para el producto.');
        return false;
    }

    // Descripción
    if (descripcion === '') {
        alert('La descripción del producto no puede estar en blanco.');
        return false;
    }
    if (descripcion.length < 10 || descripcion.length > 1000) {
        alert('La descripción del producto debe tener entre 10 y 1000 caracteres.');
        return false;
    }

    return true;
}

// -------- Verificar unicidad del código al salir del campo (onblur) --------

function verificarCodigo() {
    const codigo = document.getElementById('codigo').value.trim();
    if (!codigo) return;

    // Solo verificar si ya cumple el formato mínimo
    if (!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{5,15}$/.test(codigo)) return;

    const formData = new FormData();
    formData.append('solo_verificar', '1');
    formData.append('codigo', codigo);

    fetch('/php/verificar_codigo.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.existe) {
                alert('El código del producto ya está registrado.');
                document.getElementById('codigo').value = '';
                document.getElementById('codigo').focus();
            }
        });
}

// -------- Envío del formulario por AJAX --------

function enviarFormulario() {
    const formData = new FormData(document.getElementById('form-producto'));
    const mensaje  = document.getElementById('mensaje');

    fetch('/php/guardar_producto.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        mensaje.style.display = 'block';

        if (data.success) {
            mensaje.className = 'mensaje exito';
            mensaje.textContent = data.mensaje;
            document.getElementById('form-producto').reset();
            // Limpiar sucursales al resetear el formulario
            document.getElementById('sucursal').innerHTML = '<option value="">-- Seleccione una sucursal --</option>';
        } else {
            mensaje.className = 'mensaje error';
            mensaje.textContent = data.error || 'Error desconocido.';
            // Si el error es de código duplicado, mostrar alert específico
            if (data.error && data.error.includes('ya está registrado')) {
                alert('El código del producto ya está registrado.');
            }
        }
    })
    .catch(() => {
        mensaje.style.display = 'block';
        mensaje.className = 'mensaje error';
        mensaje.textContent = 'Error de conexión con el servidor.';
    });
}