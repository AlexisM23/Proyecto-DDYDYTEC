<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario_form = trim($_POST["usuario"]);
    $pass_form = trim($_POST["password"]);
    $tipo_form = $_POST["tipo_usuario"];

    // 1. Buscamos al usuario SOLO por su nombre y tipo
    // Eliminamos 'password=?' de la consulta SQL
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre=? AND tipo_usuario=?");
    
    if ($stmt) {
        $stmt->bind_param("ss", $usuario_form, $tipo_form);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0){
            $datos = $result->fetch_assoc(); 

            // 2. USAMOS password_verify para comparar el texto plano con el hash de la BD
            if (password_verify($pass_form, $datos['password'])) {
                
                $_SESSION['id_usuario'] = $datos['id_usuario']; 
                $_SESSION['nombre'] = $datos['nombre'];
                $_SESSION['tipo'] = $datos['tipo_usuario'];

                if($tipo_form == "admin"){
                    header("Location: admin/index_admin.php");
                } elseif($tipo_form == "caja"){
                    header("Location: caja/index_pedido.php");
                } else {
                    header("Location: cliente/index_cliente.php");
                }
                exit();
            } else {
                // Si la contraseña no coincide con el hash
                echo "<script>alert('Error: Contraseña incorrecta'); window.location.href='login.php';</script>";
            }
        } else {
            // Si no existe el usuario con ese nombre y ese tipo
            echo "<script>alert('Error: Usuario no encontrado o tipo de cuenta incorrecto'); window.location.href='login.php';</script>";
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - DIDDYTEC</title>
    <link rel="stylesheet" href="Estilos.css">
</head>
<body class="body-login">
    <div class="contenedor-login">
        <form action="login.php" method="POST"> 
            <h2>☕ Iniciar Sesión</h2>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            
            <select name="tipo_usuario" required>
                <option value="cliente">Cliente</option>
                <option value="caja">Caja</option>
                <option value="admin">Administrador</option>
            </select>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>