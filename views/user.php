<!DOCTYPE html>
<html lang="es">    
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Lista de Usuarios</title>
  </head>
  <body>
    <div class="container is-fluid">
      <br>
      <div class="col-xs-12">
        <h1>Lista de usuarios</h1>
        <br>
      <div>
          <a class="btn btn-success" href="../index.php">Nuevo usuario</a>
      </div>
      <br><br>
        <!-- Tabla -->
      <table class="table table-striped table-dark" id="table_id">                   
          <thead>    
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Password</th>
              <th>Teléfono</th>
              <th>Fecha</th>
              <th>Acciones</th>      
            </tr>
          </thead>
          <tbody>
            <?php
              $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");

              if (!$conexion) {
                die("Error de conexión: " . mysqli_connect_error());
              }

              $SQL = "SELECT * FROM user";
              $datos = mysqli_query($conexion, $SQL);

              if ($datos && mysqli_num_rows($datos) > 0) {
                while ($fila = mysqli_fetch_assoc($datos)) {
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['password']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td> 
                    <td>        
                      <a class="btn btn-warning" href="./editar_user.php?id=<?php echo $fila['id'] ?>">
                        Editar
                      </a>

                      <a class="btn btn-danger" href="./eliminar_user.php?id=<?php echo $fila['id'] ?>">
                         Eliminar
                      </a>
                    </td>           
                  </tr>
                  <?php
                }
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
            crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    
    <script>
      //Funcionalidad de la tabla creada con DataTables para mejorar la visualización,requisito utiliar script
      //https://datatables.net/manual/installation#Include-jQuery

      $(document).ready(function () {
        $('#table_id').DataTable();
      });
    </script>
  </body>
</html>
