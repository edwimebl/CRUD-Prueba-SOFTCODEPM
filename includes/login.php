<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #login { margin-top: 50px; }
        #login-box { padding: 20px; border: 1px solid #ccc; background: #f7f7f7; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container" id="login">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div id="login-box">
                <h3 class="text-center">Iniciar Sesión</h3>
                <form id="formLogin">
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success" id="btnCrearUsuario">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#formLogin").submit(function(e) {
        e.preventDefault(); // Evitar recarga de la página

        const correo = $("#correo").val().trim();
        const password = $("#password").val().trim();

        $.ajax({
            url: "_funciones.php",
            method: "POST",
            dataType: "json",
            data: {
                accion: "acceso_user",
                correo: correo,
                password: password
            },
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Bienvenido',
                        text: response.mensaje,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirigir a la vista correspondiente según el rol
                        window.location.href = response.url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema con el servidor'
                });
            }
        });
    });
});
</script>


</body>
</html>
