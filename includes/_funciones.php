<?php
require_once("_db.php");

// === Obtener un usuario para edición (AJAX GET) ===
if (isset($_GET['accion']) && $_GET['accion'] === 'obtener_usuario') {
    header('Content-Type: application/json; charset=utf-8');

    $id = intval($_GET['id'] ?? 0);
    $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");

    if (!$conexion) {
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión']); 
        exit;
    }

    $resultado = mysqli_query($conexion, "SELECT * FROM user WHERE id = $id");

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        // No mandamos la contraseña real, solo la longitud como ***
        $usuario['password'] = str_repeat('*', strlen($usuario['password']));
        echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    }
    exit;
}

// === Dispatcher para JSON (fetch con application/json) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
    $json = json_decode(file_get_contents('php://input'), true);

    if (isset($json['accion'])) {
        switch ($json['accion']) {
            case 'editar_registro':
                editar_registro($json); 
                exit; // 🔹 Salimos aquí para no enviar otra respuesta
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'Acción JSON no reconocida']); 
    exit;
}

// === Dispatcher clásico para formularios ===
if (isset($_POST['accion'])) { 
    switch ($_POST['accion']) {
        case 'editar_registro':
            editar_registro($_POST);
            exit;
        case 'eliminar_registro':
            eliminar_registro();
            exit;
        case 'acceso_user':
            acceso_user();
            exit;
    }
}

// === Editar registro (soporta JSON y formulario) ===

function editar_registro($data = null) {
    $conexion = mysqli_connect("localhost", "root", "", "crud_prueba");

    if (is_array($data)) {
        header('Content-Type: application/json; charset=utf-8');

        $id = intval($data['id'] ?? 0);

        // Obtener valores actuales
        $resActual = mysqli_query($conexion, "SELECT * FROM user WHERE id = $id");
        if (!$resActual || mysqli_num_rows($resActual) === 0) {
            echo json_encode(['status'=>'error','message'=>'Usuario no encontrado']);
            exit;
        }
        $actual = mysqli_fetch_assoc($resActual);

        // Usar valor nuevo solo si no está vacío
        $nombre   = !empty(trim($data['nombre'] ?? '')) ? mysqli_real_escape_string($conexion, $data['nombre']) : $actual['nombre'];
        $correo   = !empty(trim($data['correo'] ?? '')) ? mysqli_real_escape_string($conexion, $data['correo']) : $actual['correo'];
        $telefono = !empty(trim($data['telefono'] ?? '')) ? mysqli_real_escape_string($conexion, $data['telefono']) : $actual['telefono'];

        // Manejo de contraseña: solo cambiar si envían nueva y no es '***'
        if (!empty($data['password']) && $data['password'] !== str_repeat('*', strlen($actual['password']))) {
            $password = mysqli_real_escape_string($conexion, $data['password']);
        } else {
            $password = $actual['password'];
        }

        // Rol: mantener si no se envía o se envía vacío
        $rol = isset($data['rol']) && $data['rol'] !== '' ? intval($data['rol']) : intval($actual['rol']);

        // Ejecutar actualización
        $sql = "UPDATE user SET 
                    nombre='$nombre',
                    correo='$correo',
                    telefono='$telefono',
                    password='$password',
                    rol=$rol
                WHERE id=$id";

        $ok = mysqli_query($conexion, $sql);

        if ($ok) {
            $resNuevo = mysqli_query($conexion, "SELECT * FROM user WHERE id = $id");
            $usuarioActualizado = mysqli_fetch_assoc($resNuevo);
            $usuarioActualizado['password'] = str_repeat('*', strlen($usuarioActualizado['password']));

            echo json_encode([
                'status'=>'success',
                'message'=>'Usuario actualizado correctamente',
                'usuario'=>$usuarioActualizado
            ]);
        } else {
            echo json_encode([
                'status'=>'error',
                'message'=>'Error al actualizar: '.mysqli_error($conexion)
            ]);
        }
        exit;
    }
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
    } else if($filas['rol'] == 2){
        header('Location: ../views/lector.php');
    } else {
        header("Location: ../includes/login.php");
        $_SESSION['error'] = "Usuario o contraseña incorrectos";
        exit();
        session_destroy();  
    }
}
?>
