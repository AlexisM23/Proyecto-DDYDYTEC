<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Limpiamos los datos para evitar errores de espacios
    $user_input = trim($_POST["usuario"]); 
    $pass_input = trim($_POST["password"]);
    $tipo_input = trim($_POST["tipo_usuario"]);

    // 2. Usamos una consulta que busque tanto por ID como por NOMBRE
    // Esto es más seguro y fácil para ti
    $sql = "SELECT * FROM usuarios WHERE (id_usuario = ? OR nombre = ?) AND password = ? AND tipo_usuario = ?";
    $stmt = $conexion->prepare($sql);
    
    // Aquí le pasamos el dato dos veces (para id y para nombre)
    $stmt->bind_param("ssss", $user_input, $user_input, $pass_input, $tipo_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $datos = $result->fetch_assoc(); 

        // Guardamos en la sesión
        $_SESSION['id_usuario'] = $datos['id_usuario']; 
        $_SESSION['nombre'] = $datos['nombre'];
        $_SESSION['tipo'] = $datos['tipo_usuario'];

        // Redirección
        if($tipo_input == "admin") {
            header("Location: admin/index_admin.php");
        } elseif($tipo_input == "caja") {
            header("Location: caja/index_pedido.php");
        } else {
            header("Location: cliente/index_cliente.php");
        }
        exit();
    } else {
        // Si falla, te manda de regreso
        echo "<script>alert('ERROR: Usuario, contraseña o tipo de cuenta incorrectos'); window.location.href='pagprincipal.php';</script>";
    }
}
?>