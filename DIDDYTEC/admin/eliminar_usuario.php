<?php
include "../conexion.php";

// 1. Obtenemos el ID que viene por la URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // 2. Preparamos la orden de eliminar
    $sql = "DELETE FROM usuarios WHERE id_usuario = '$id'";

    // 3. Ejecutamos y regresamos a la lista
    if($conexion->query($sql)){
        header("Location: usuarios.php");
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    echo "No se recibió un ID válido.";
}
$conexion->close();
?>