<?php

    $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");
        $id= $_GET['id'];
        $consulta=mysqli_query($conexion, "DELETE FROM user WHERE id= '$id'");

        header('Location: user.php');

?>
