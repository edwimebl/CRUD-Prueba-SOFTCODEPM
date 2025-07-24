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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eliminar Usuarios</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-6 offset-sm-3">
                    <div class="alert alert-danger text-center">
                        <p>¿Estás seguro de que deseas eliminar este usuario?</p>                    
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 ">
                <form action="../includes/_funciones.php" method="POST">
                    <input type="hidden" name="accion" value="eliminar_registro">
                    <input type="hidden" name="id" value="<?php echo $_GET ['id']; ?>">
                    <input type="submit" name="" value="Eliminar" class="btn btn-success btn-block">
                    <a href="user.php" ></a>
                </form>

            </div>
        </div>            
    </body>
</html>