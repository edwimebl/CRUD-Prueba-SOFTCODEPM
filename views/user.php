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
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">   
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <title>Usuarios</title>
</head>
  <br>
<div class="container is-fluid">


<div class="col-xs-12">
  		<h1>Bienvenido Administrador <?php echo $_SESSION['nombre']; ?></h1>
      <br>
		<h1>Lista de usuarios</h1>
    <br>
  <!-- <p> Mostrar cantidad de <select name="sel" id="value"> 
        <option value="1">1 Registro</option>
        <option value="2">2 Registros</option>
        <option value="3">3 Registros</option>
    </select>
    <br>-->
    <br>
		<div>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create">
				<span class="glyphicon glyphicon-plus">Nuevo usuario </span> 
        <i class="fas fa-plus"></i>
      </button>

      <a class="btn btn-warning" href="../includes/sesion/cerrarSesion.php">Log Out
        <i class="fas fa-power-off" aria-hidden="true"></i>
      </a>
		</div>
		<br>


<!-- Aquí puedes escribir tu comentario 
    <div class="container-fluid"> 
  <form class="d-flex">
			<form action="" method="GET">
			<input class="form-control me-2" type="search" placeholder="Buscar con PHP" 
			name="busqueda"> <br>
			<button class="btn btn-outline-info" type="submit" name="enviar"> <b>Buscar </b> </button> 
			</form>
  </div>-->
  <?php
$conexion=mysqli_connect("localhost","root","","crud_prueba"); 
$where="";

if(isset($_GET['enviar'])){
  $busqueda = $_GET['busqueda'];


	if (isset($_GET['busqueda']))
	{
		$where="WHERE user.correo LIKE'%".$busqueda."%' OR nombre  LIKE'%".$busqueda."%'
    OR telefono  LIKE'%".$busqueda."%'";
	}
  
}


?>
   


			</form>
     <!-- <div class="container-fluid">
  <form class="d-flex">
      <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" 
      placeholder="Buscar con JS">
      <hr>
      </form>
  </div>  -->

  <br>


      <table class="table table-striped table-dark table_id " id="table_id">

                   
                         <thead>    
                         <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Password</th>
                        <th>Telefono</th>
                        <th>Fecha</th>
                        <th>Rol</th>
                        <th>Acciones</th>
         
                        </tr>
                        </thead>
                        <tbody>

				<?php

$conexion=mysqli_connect("localhost","root","","crud_prueba");               
$SQL="SELECT user.id, user.nombre, user.correo, user.password, user.telefono,
user.fecha, permisos.rol FROM user
LEFT JOIN permisos ON user.rol = permisos.id $where";
$dato = mysqli_query($conexion, $SQL);

if($dato -> num_rows >0){
    while($fila=mysqli_fetch_array($dato)){
    
?>
<tr>
  <td><?php echo $fila['nombre']; ?></td>
  <td><?php echo $fila['correo']; ?></td>
  <td><?php echo $fila['password']; ?></td>
  <td><?php echo $fila['telefono']; ?></td>
  <td><?php echo $fila['fecha']; ?></td>
  <td><?php echo $fila['rol']; ?></td>



  <td>
    <a class="btn btn-warning" href="editar_user.php?id=<?php echo $fila['id']?> ">
    <i class="fa fa-edit"></i> </a>

    <a class="btn btn-danger" href="eliminar_user.php?id=<?php echo $fila['id']?> ">
    <i class="fa fa-trash"></i></a>

  </td>
</tr>

<?php
}
}else{

    ?>
    <tr class="text-center">
    <td colspan="16">No existen registros</td>
    </tr>
    
    <?php
    
}

?>

	</body>
  </table>
  <!-- <div id="paginador" class=""></div>-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" 
          integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
          crossorigin="anonymous">
  </script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js">
        $(document).ready(function () {
          $('#table_id').DataTable();
        });
</script>      

<script src="../package/dist/sweetalert2.all.js"></script>
<script src="../package/dist/sweetalert2.all.min.js"></script>
<script src="../package/jquery-3.6.0.min.js"></script>

<script src="../js/page.js"></script>
<script src="../js/buscador.js"></script>
<script src="../js/user.js"></script>




		<?php include('../index.php'); ?>
</html>