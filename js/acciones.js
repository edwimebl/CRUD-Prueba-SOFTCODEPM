$(document).ready(function () {
    $(document).on('click', '.edit', function () {
        var id = $(this).val();
        var nombre = $('#nombre' + id).text();
        var precio = $('#correo' + id).text();
        var imagen = $('#password' + id).text();
        var imagen = $('#telefono' + id).text();
        var imagen = $('#fecha' + id).text();
        var imagen = $('#rol' + id).text();


    });

    $(document).on('click', '.delete', function () {
        var id = $(this).val();
        var folio = $('#id' + id).text();
        var name = $('#nombre' + id).text();

        $('#id').val(folio);
        document.getElementById('name_user').innerHTML = name;
        $('#delete').modal('show');

    });

});

// Actualizar la imagen en la edicion
function updateimage() {
    const $seleccionArchivos = document.querySelector("#imagen"),
        $imagenPrevisualizacion = document.querySelector("#img");

    // Escuchar cuando cambie
    $seleccionArchivos.addEventListener("change", () => {
        // Los archivos seleccionados, pueden ser muchos o uno
        const archivos = $seleccionArchivos.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
            $imagenPrevisualizacion.src = "";
            return;
        }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const primerArchivo = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(primerArchivo);
        // Y a la fuente de la imagen le ponemos el objectURL
        $imagenPrevisualizacion.src = objectURL;
    });
}

// acciones para el modal eliminar con sweet alert

 $('.btn-del').on('click', function(e){
      e.preventDefault();
      const href = $(this).attr('href');
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás deshacer esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
      }).then((result) => {
        if (result.value) {
          if (result.isConfirmed) {
            Swal.fire(
              'Eliminado!',
              'El registro ha sido eliminado.',
              'success'
            ).then(() => {
              document.location.href = href;
            });
          }
        }
      })
    });
  // === EDITAR USUARIO CON SWEETALERT2 (resalte suave + mensaje por campo) ===
document.addEventListener('click', async function (e) {
  const btn = e.target.closest('.btn-editar');
  if (!btn) return;

  const idUsuario = btn.dataset.id || btn.getAttribute('data-id');
  if (!idUsuario) return;

  try {
    const res = await fetch(`../includes/_funciones.php?accion=obtener_usuario&id=${encodeURIComponent(idUsuario)}`);
    const usuario = await res.json();

    if (!usuario || usuario.status === 'error') {
      Swal.fire('Error', usuario?.message || 'No se encontraron datos', 'error');
      return;
    }

    Swal.fire({
      title: 'Editar usuario',
      html: `
        <style>
          .input-highlight { box-shadow: 0 0 10px rgba(13,110,253,0.25) !important; }
          .msg-blue { color: #0d6efd; font-size: 0.85em; margin-top: 4px; display: none; }
        </style>

        <input type="hidden" id="id" value="${esc(usuario.id)}">

        <input type="text" id="nombre" class="swal2-input" value="${esc(usuario.nombre || '')}" placeholder="Nombre">
        <span id="error-nombre" class="msg-blue">Este campo es obligatorio</span>

        <input type="email" id="correo" class="swal2-input" value="${esc(usuario.correo || '')}" placeholder="Correo">
        <span id="error-correo" class="msg-blue">Este campo es obligatorio</span>

        <input type="tel" id="telefono" class="swal2-input" value="${esc(usuario.telefono || '')}" placeholder="Teléfono">
        <span id="error-telefono" class="msg-blue">Este campo es obligatorio</span>
        <input type="password" id="password" class="swal2-input" value="***" placeholder="Nueva contraseña (opcional)">

        <select id="rol" class="swal2-input">
          <option value="">Seleccione un rol</option>
          <option value="1" ${String(usuario.rol) === '1' ? 'selected' : ''}>Administrador</option>
          <option value="2" ${String(usuario.rol) === '2' ? 'selected' : ''}>Usuario</option>
        </select>
        
        <span id="error-rol" class="msg-blue">Seleccione un rol</span>

        
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar cambios',
      cancelButtonText: 'Cancelar',

      preConfirm: () => {
        const popup = Swal.getPopup();
        const idVal = popup.querySelector('#id').value;
        const nombreEl = popup.querySelector('#nombre');
        const correoEl = popup.querySelector('#correo');
        const telefonoEl = popup.querySelector('#telefono');
        const rolEl = popup.querySelector('#rol');
        const passwordEl = popup.querySelector('#password');

        // limpiar estados previos
        [nombreEl, correoEl, telefonoEl, rolEl].forEach(el => {
          el.classList.remove('input-highlight');
          const err = popup.querySelector(`#error-${el.id}`);
          if (err) err.style.display = 'none';
        });

        // validar y marcar el primer error encontrado
        let firstError = null;
        if (!nombreEl.value.trim()) {
          nombreEl.classList.add('input-highlight');
          popup.querySelector('#error-nombre').style.display = 'block';
          firstError = nombreEl;
        }
        if (!correoEl.value.trim()) {
          correoEl.classList.add('input-highlight');
          popup.querySelector('#error-correo').style.display = 'block';
          if (!firstError) firstError = correoEl;
        }
        if (!telefonoEl.value.trim()) {
          telefonoEl.classList.add('input-highlight');
          popup.querySelector('#error-telefono').style.display = 'block';
          if (!firstError) firstError = telefonoEl;
        }
        if (!rolEl.value) {
          rolEl.classList.add('input-highlight');
          popup.querySelector('#error-rol').style.display = 'block';
          if (!firstError) firstError = rolEl;
        }

        if (firstError) {
          firstError.focus();
          return false; // evita el envío hasta corregir
        }

        // preparar payload (solo enviar password si cambió)
        const payload = {
          accion: 'editar_registro',
          id: idVal,
          nombre: nombreEl.value.trim(),
          correo: correoEl.value.trim(),
          telefono: telefonoEl.value.trim(),
          rol: rolEl.value
        };
        if (passwordEl.value && passwordEl.value !== '***') payload.password = passwordEl.value.trim();

        return fetch('../includes/_funciones.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(json => {
          if (!json || json.status !== 'success') throw new Error(json?.message || 'Error al actualizar');
          return json;
        })
        .catch(err => {
          // Mensaje global en caso de fallo de red/servidor
          Swal.showValidationMessage(`Error: ${err.message || err}`);
        });
      },

      didOpen: () => {
        // limpiar estilo de los campos en tiempo real al escribir / cambiar select
        const popup = Swal.getPopup();
        const inputs = popup.querySelectorAll('.swal2-input, select');
        inputs.forEach(inp => {
          inp.addEventListener('input', () => {
            inp.classList.remove('input-highlight');
            const err = popup.querySelector(`#error-${inp.id}`);
            if (err) err.style.display = 'none';
          });
          inp.addEventListener('change', () => {
            inp.classList.remove('input-highlight');
            const err = popup.querySelector(`#error-${inp.id}`);
            if (err) err.style.display = 'none';
          });
        });
      }
    }).then(result => {
      if (result.isConfirmed && result.value) {
        Swal.fire('✅ Actualizado', 'Usuario actualizado correctamente', 'success')
          .then(() => location.reload());
      }
    });
  } catch (err) {
    console.error(err);
    Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
  }
});

// utilidad para escapar
function esc(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
    .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
