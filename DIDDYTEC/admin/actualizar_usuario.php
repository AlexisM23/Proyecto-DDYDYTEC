<?php
include "../conexion.php";
session_start(); // Buena práctica mantener activa la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiamos los datos para evitar fallos por caracteres extraños
    $id = $conexion->real_escape_string($_POST['id_usuario']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $tipo = $conexion->real_escape_string($_POST['tipo_usuario']);
    $pass = $_POST['password'];

    // Validar que el ID no haya llegado vacío
    if (empty($id)) {
        echo "<script>
                alert('Error: No se recibió el ID del usuario.');
                window.history.back();
              </script>";
        exit();
    }

    if (!empty($pass)) {
        // Si escribió una nueva contraseña, la encriptamos
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre='$nombre', tipo_usuario='$tipo', password='$pass_hash' WHERE id_usuario='$id'";
    } else {
        // Si no escribió nada, solo actualizamos nombre y tipo
        $sql = "UPDATE usuarios SET nombre='$nombre', tipo_usuario='$tipo' WHERE id_usuario='$id'";
    }

    if ($conexion->query($sql)) {
        // Modificado para que vuelva a tu estructura de vistas del panel Admin
        echo "<script>
                alert('¡Usuario actualizado con éxito!');
                window.location.href = 'index_admin.php?vista=usuarios';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error al actualizar en la Base de Datos: " . $conexion->error . "');
                window.history.back();
              </script>";
    }
}

$conexion->close();
?>