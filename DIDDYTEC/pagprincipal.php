<?php
// 1. INICIO DE SESIÓN Y CONEXIÓN
session_start(); // Inicia el motor de sesiones de PHP para rastrear al usuario en diferentes páginas
include "conexion.php"; // Incluye el archivo que conecta con la base de datos MySQL

// 2. PROCESAMIENTO DEL FORMULARIO (Solo ocurre cuando se presiona el botón "Entrar")
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Limpiamos los datos recibidos para eliminar espacios en blanco accidentales
    $usuario_form = trim($_POST["usuario"]);
    $pass_form = trim($_POST["password"]);
    $tipo_form = $_POST["tipo_usuario"];

    // 3. PREPARACIÓN DE LA CONSULTA (Seguridad contra Inyección SQL)
    // El "?" actúa como marcador de posición para los datos reales
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre=? AND password=? AND tipo_usuario=?");
    
    if ($stmt) {
        // "sss" indica que pasaremos 3 variables de tipo String (cadena)
        $stmt->bind_param("sss", $usuario_form, $pass_form, $tipo_form);
        $stmt->execute(); // Ejecuta la consulta en la base de datos
        $result = $stmt->get_result(); // Obtiene el conjunto de resultados

        // 4. VERIFICACIÓN DE CREDENCIALES
        if ($result->num_rows > 0){
            // Si hay una fila, significa que el usuario y contraseña coinciden
            $datos = $result->fetch_assoc(); // Convierte el resultado en un array asociativo

            // Guardamos información importante en la SESIÓN para usarla en otras páginas
            $_SESSION['id_usuario'] = $datos['id_usuario']; 
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['tipo'] = $datos['tipo_usuario']; 

            // 5. REDIRECCIÓN SEGÚN ROL (Toma de decisiones)
            if($tipo_form == "admin"){
                header("Location: admin/index_admin.php"); // Envía al panel de administrador
            } elseif($tipo_form == "caja"){
                header("Location: caja/index_pedido.php"); // Envía al panel de punto de venta/caja
            } else {
                header("Location: cliente/index_cliente.php"); // Envía al catálogo de cliente
            }
            exit(); // Detiene el script para asegurar que la redirección ocurra
        } else {
            // Si no hay resultados, mostramos un error mediante JavaScript
            echo "<script>
                    alert('Error: Datos incorrectos para el usuario: " . htmlspecialchars($usuario_form) . "');
                    window.location.href='login.php';
                  </script>";
        }
        $stmt->close(); // Cierra la sentencia preparada
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
}
?>

<?php
// Bloque de seguridad redundante para asegurar que la sesión esté disponible al cargar el HTML
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