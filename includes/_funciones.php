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
        password ='$password', rol = '$rol' WHERE id = '$id' ";
        mysqli_query($conexion, $consulta);
        header('Location: ../views/user.php');
    }

    function eliminar_registro() {
        $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");
        extract($_POST);
        $id= $_POST['id'];
        $consulta= "DELETE FROM user WHERE id= $id";

        mysqli_query($conexion, $consulta);


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
        $filas=mysqli_fetch_array($resultado);

        if($filas['rol'] == 1){
            header('Location: ../views/user.php');
        }else if($filas['rol'] == 2){
            header('Location: ../views/lector.php');
        }else{
            header("Location: ../includes/login.php");
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            exit();
            session_destroy();  
        }
                  
    }

?>