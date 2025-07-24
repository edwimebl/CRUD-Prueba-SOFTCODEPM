<?php
  session_start();
  error_reporting(0);

  $validar = $_SESSION['nombre'];

  if( $validar == null || $validar = ''){

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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <!-- Estilos personalizados -->
     <!-- CDN de Font Awesome (gratuito) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">+
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/es.css">
    <title>Lista de Usuarios</title>
  </head>
  
  <body>
    <div class="container is-fluid">
      <br>
      <div class="col-xs-12">
        <h1>Bienvenido <?php echo $_SESSION['nombre']; ?></h1>
        <br>
        <h1>Lista de usuarios</h1>
        <br>
      <div>
          <a class="btn btn-warning" href="../includes/sesion/cerrarSesion.php">Log Out
            <i class="fas fa-power-off" aria-hidden="true">              
            </i>
          </a>
      </div>
      <br><br>
        <!-- Tabla -->
      <table class="table table-striped table-dark" id="table_id">                   
          <thead>    
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Teléfono</th>
              <th>Fecha Actualización</th>
              <th>Rol</th>      
            </tr>
          </thead>
          <tbody>
            <?php
              $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");

              if (!$conexion) {
                die("Error de conexión: " . mysqli_connect_error());
              }

              $SQL = "SELECT user.id, user.nombre, user.correo, user.password, user.telefono,
                        user.fecha, permisos.rol FROM user
                        LEFT JOIN permisos ON user.rol = permisos.id";
              $datos = mysqli_query($conexion, $SQL);

              if ($datos && mysqli_num_rows($datos) > 0) {
                while ($fila = mysqli_fetch_assoc($datos)) {
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td> 
                    <td><?php echo htmlspecialchars($fila['rol']); ?></td>                             
                  </tr>
                  <?php
                }
                }else {
                echo "<tr><td colspan='16'> No hay registros disponibles </td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
            crossorigin="anonymous">
    </script>

    <script 
            type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js">
    </script>
    
    <script>
      //Funcionalidad de la tabla creada con DataTables para mejorar la visualización,requisito utiliar
      src="https://datatables.net/manual/installation#Include-jQuery"

      $(document).ready(function () {
        $('#table_id').DataTable();
      });
    </script>
    <script src="../js/user.js"></script>
  </body>
</html>