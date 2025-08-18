<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];
if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/page.css">

    <title>Usuarios</title>
</head>
<body>
<div class="container is-fluid">
    <br>
    <div class="col-xs-12">
        <h1>Bienvenido Administrador <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
        <br>
        <h1>Lista de usuarios</h1>
        <br>

        <!-- Botones -->
        <div class="mb-3">
            <button type="button" class="btn btn-success" id="btnCrearUsuario">
                <i class="fas fa-plus"></i> Nuevo usuario
            </button>

            <a class="btn btn-warning" href="../includes/sesion/cerrarSesion.php">
                Log Out <i class="fas fa-power-off"></i>
            </a>
        </div>

        <!-- Tabla de usuarios -->
        <table class="table table-striped table-dark" id="table_id">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Password</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conexion = mysqli_connect("localhost","root","","crud_prueba"); 
                if (!$conexion) die("Error de conexión: " . mysqli_connect_error());

                $SQL = "SELECT user.id, user.nombre, user.correo, user.password, user.telefono,
                                user.fecha, permisos.rol 
                        FROM user
                        LEFT JOIN permisos ON user.rol = permisos.id";
                $datos = mysqli_query($conexion, $SQL);

                if ($datos && mysqli_num_rows($datos) > 0) {
                    while($fila = mysqli_fetch_assoc($datos)) {
                        echo "<tr>
                                <td>".htmlspecialchars($fila['nombre'])."</td>
                                <td>".htmlspecialchars($fila['correo'])."</td>
                                <td>".htmlspecialchars($fila['password'])."</td>
                                <td>".htmlspecialchars($fila['telefono'])."</td>
                                <td>".htmlspecialchars($fila['fecha'])."</td>
                                <td>".htmlspecialchars($fila['rol'])."</td>
                                <td>
                                    <a class='btn btn-warning btn-editar' data-id='{$fila['id']}'><i class='fa fa-edit'></i></a>
                                    <a class='btn btn-danger btn-del' href='eliminar_user.php?id={$fila['id']}'><i class='fa fa-trash'></i></a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay registros disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery y Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts personalizados -->
<script src="../js/page.js"></script>
<script src="../js/buscador.js"></script>
<script src="../js/user.js"></script>
<script src="../js/acciones.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTables solo si NO está inicializada
    if (! $.fn.DataTable.isDataTable('#table_id') ) {
        $('#table_id').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "ordering": true,
            "paging": true,
            "searching": true
        });
    }
});
</script>

</body>
</html>
