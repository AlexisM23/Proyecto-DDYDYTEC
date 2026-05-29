<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST["usuario"]); 
    $pass_input = trim($_POST["password"]);
    $tipo_input = trim($_POST["tipo_usuario"]);

    // Buscamos al usuario por ID o Nombre (SIN la contraseña en el SQL)
    $sql = "SELECT * FROM usuarios WHERE id_usuario = ? OR nombre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($datos = $result->fetch_assoc()) {
        // VERIFICACIÓN: ¿La clave coincide con el hash?
        if (password_verify($pass_input, $datos['password'])) {
            
            // ¿El tipo de usuario es correcto?
            if ($datos['tipo_usuario'] == $tipo_input) {
                $_SESSION['id_usuario'] = $datos['id_usuario']; 
                $_SESSION['nombre'] = $datos['nombre'];
                $_SESSION['tipo'] = $datos['tipo_usuario'];

                // Redirección según tu estructura en la imagen
                if($tipo_input == "admin") header("Location: admin/index_admin.php");
                elseif($tipo_input == "caja") header("Location: caja/index_pedido.php");
                else header("Location: cliente/index_cliente.php");
                exit();
            } else {
                die("Error: El usuario existe y la clave es correcta, pero el TIPO no es " . $tipo_input);
            }
        } else {
            die("Error: La contraseña no coincide con el hash guardado en la base de datos.");
        }
    } else {
        die("Error: No existe ningún usuario con el ID o Nombre: " . $user_input);
    }
}
?>