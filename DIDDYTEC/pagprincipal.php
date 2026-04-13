<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario_form = trim($_POST["usuario"]);
    $pass_form = trim($_POST["password"]);
    $tipo_form = $_POST["tipo_usuario"];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre=? AND password=? AND tipo_usuario=?");
    
    if ($stmt) {
        $stmt->bind_param("sss", $usuario_form, $pass_form, $tipo_form);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0){
            $datos = $result->fetch_assoc(); 

            $_SESSION['id_usuario'] = $datos['id_usuario']; 
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['tipo'] = $datos['tipo_usuario']; // Guardamos el tipo por seguridad

            // CORRECCIÓN DE RUTAS: 
            // Como este archivo está en la raíz, entramos directo a las carpetas
            if($tipo_form == "admin"){
                header("Location: admin/index_admin.php");
            } elseif($tipo_form == "caja"){
                header("Location: caja/index_pedido.php");
            } else {
                header("Location: cliente/index_cliente.php");
            }
            exit();
        } else {
            echo "<script>
                    alert('Error: Datos incorrectos para el usuario: " . htmlspecialchars($usuario_form) . "');
                    window.location.href='login.php';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
}
?>
<?php
include "conexion.php";

// Solo inicia la sesión si no hay una activa para evitar el error visual
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - DIDDYTEC</title>
    <link rel="stylesheet" href="Estilos.css"> </head>
<body class="body-login">
    <div class="contenedor-login">
        <form action="login.php" method="POST"> <h2>☕ Iniciar Sesión</h2>
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