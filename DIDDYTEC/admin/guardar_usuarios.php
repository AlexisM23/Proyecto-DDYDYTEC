<?php
include "../conexion.php";

// 1. Limpiamos los datos para evitar errores con caracteres especiales
$id = $conexion->real_escape_string($_POST['id_usuario']); 
$nombre = $conexion->real_escape_string($_POST['nombre']);
$tipo = $conexion->real_escape_string($_POST['tipo']);

// 2. Encriptamos la contraseña (esto ya lo tenías bien)
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// 3. Ejecutamos la consulta
$sql = "INSERT INTO usuarios (id_usuario, nombre, password, tipo_usuario) 
        VALUES ('$id', '$nombre', '$password', '$tipo')";

if($conexion->query($sql)){
    // Redirige al panel con el diseño de DIDDYTEC
    header("Location: index_admin.php?vista=usuarios");
    exit(); // Detiene la ejecución después de redirigir
} else {
    echo "Error: " . $conexion->error;
}

$conexion->close();
?>