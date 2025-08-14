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

    //acciones para editar usuario 

    document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function() {
        const idUsuario = this.getAttribute('data-id');

        // 1️⃣ Obtener datos del usuario
        fetch(`../includes/_funciones.php?accion=obtener_usuario&id=${idUsuario}`)
        .then(res => res.json())
        .then(usuario => {
            if (!usuario) {
                Swal.fire('Error', 'No se encontraron datos', 'error');
                return;
            }

            // 2️⃣ Mostrar modal con formulario
            Swal.fire({
                title: 'Editar usuario',
                html: `
                    <input type="hidden" id="id" value="${usuario.id}">
                    <input type="text" id="nombre" class="swal2-input" value="${usuario.nombre}" placeholder="Nombre">
                    <input type="email" id="correo" class="swal2-input" value="${usuario.correo}" placeholder="Correo">
                    <input type="tel" id="telefono" class="swal2-input" value="${usuario.telefono}" placeholder="Teléfono">
                    <input type="password" id="password" class="swal2-input" value="${usuario.password}" placeholder="Contraseña">
                    <select id="rol" class="swal2-input">
                        <option value="1" ${usuario.rol == 1 ? 'selected' : ''}>Administrador</option>
                        <option value="2" ${usuario.rol == 2 ? 'selected' : ''}>Usuario</option>
                    </select>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar cambios',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                preConfirm: () => {
                    const datos = {
                        accion: 'editar_registro',
                        id: document.getElementById('id').value,
                        nombre: document.getElementById('nombre').value,
                        correo: document.getElementById('correo').value,
                        telefono: document.getElementById('telefono').value,
                        password: document.getElementById('password').value,
                        rol: document.getElementById('rol').value
                    };
                    return fetch('../includes/_funciones.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(datos)
                    }).then(res => res.json());
                }
            }).then(result => {
                if (result.isConfirmed) {
                    if (result.value.status === 'success') {
                        Swal.fire('Actualizado', result.value.message, 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error', result.value.message, 'error');
                    }
                }
            });
        });
    });
});