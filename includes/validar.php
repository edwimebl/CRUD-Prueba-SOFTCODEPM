<?php
  $conexion= mysqli_connect("localhost", "root", "", "crud_prueba");

  if(isset($_POST['registrar'])){

      if(strlen($_POST['nombre']) >=1 && strlen($_POST['correo'])  >=1 && strlen($_POST['telefono'])  >=1 
      && strlen($_POST['password'])  >=1 ){

      $nombre = trim($_POST['nombre']);
      $correo = trim($_POST['correo']);
      $telefono = trim($_POST['telefono']);
      $password = trim($_POST['password']);
      $rol = trim($_POST['rol']);

      // Validar si el correo ya existe
      $consulta = "SELECT * FROM user WHERE correo = '$correo'";
      $resultado = mysqli_query($conexion, $consulta);
      $filas = mysqli_fetch_assoc($resultado);

      $consulta= "INSERT INTO user (nombre, correo, telefono, password, rol)
      VALUES ('$nombre', '$correo','$telefono','$password', '$rol')";

      mysqli_query($conexion, $consulta);
      mysqli_close($conexion);
      
      header('Location: ../views/user.php');
    }
  }

?>