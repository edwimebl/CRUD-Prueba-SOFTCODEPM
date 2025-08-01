<?php
$id = $_GET['id'];
$conexion=mysqli_connect("localhost","root","","crud_prueba"); 
$query = mysqli_query($conexion,"DELETE FROM user WHERE id = '$id'");

header ('Location: prueba.php?m=1');