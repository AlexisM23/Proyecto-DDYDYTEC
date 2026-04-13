<?php
include "../conexion.php";

$id = $_POST['id_usuario']; // Recibimos el ID del formulario
$nombre = $_POST['nombre'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$tipo = $_POST['tipo'];

// IMPORTANTE: Agregamos 'id_usuario' y '$id' a la consulta
$sql = "INSERT INTO usuarios (id_usuario, nombre, password, tipo_usuario) 
        VALUES ('$id', '$nombre', '$password', '$tipo')";

if($conexion->query($sql)){
    header("Location: index_admin.php?vista=usuarios");
} else {
    echo "Error: " . $conexion->error;
}
$conexion->close();
?>