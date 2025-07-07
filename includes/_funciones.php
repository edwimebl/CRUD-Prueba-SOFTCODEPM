<?php

    require_once ("_db.php");

    if (isset($_POST['accion'])){ 
        switch ($_POST['accion']){
            //casos de registros
            case 'editar_registro':
                editar_registro();
                break; 

                case 'eliminar_registro';
                eliminar_registro();
        
                break;

                case 'acceso_user';
                acceso_user();
                break;
            }
        }

    function editar_registro() {
        $conexion=mysqli_connect("localhost","root","","crud_prueba");
        extract($_POST);
        $consulta="UPDATE user SET nombre = '$nombre', correo = '$correo', telefono = '$telefono',
        password ='$password' WHERE id = '$id' ";
        mysqli_query($conexion, $consulta);
        header('Location: ../views/user.php');
    }

    function eliminar_registro() {
        $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");
        if (!$conexion) {
          die("Connection failed: " . mysqli_connect_error());
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
          $consulta = "DELETE FROM user WHERE id = '$id'";
          mysqli_query($conexion, $consulta);
        }
        header('Location: ../views/user.php');
    }

    function acceso_user() {
        $nombre=$_POST['nombre'];
        $password=$_POST['password'];
        session_start();
        $_SESSION['nombre']=$nombre;

        $conexion=mysqli_connect("localhost","root","","crud_prueba");
        $consulta= "SELECT * FROM user WHERE nombre='$nombre' AND password='$password'";
        $resultado=mysqli_query($conexion, $consulta);
        $filas=mysqli_num_rows($resultado);

        if($filas){
            header('Location: ../views/user.php');
        }else{
            echo "<script>
                         alert('Usuario o contraseña incorrectos');
                         location.href='login.php';
                  </script>";
            session_destroy();
        }
    }

?>