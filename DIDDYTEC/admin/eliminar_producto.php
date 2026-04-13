<?php
include "../conexion.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // 1. Primero buscamos la ruta de la imagen para borrarla del servidor
    $consulta = "SELECT imagen FROM productos WHERE id_producto = '$id'";
    $resultado = $conexion->query($consulta);
    $datos = $resultado->fetch_assoc();
    
    $ruta_imagen = "../" . $datos['imagen'];

    // 2. Si el archivo existe en la carpeta, lo borramos
    if(file_exists($ruta_imagen)){
        unlink($ruta_imagen);
    }

    // 3. Ahora borramos el registro en la BD
    $sql = "DELETE FROM productos WHERE id_producto = '$id'";

    if($conexion->query($sql)){
        header("Location: index_admin.php?vista=productos");
    } else {
        echo "Error al eliminar producto: " . $conexion->error;
    }
}
$conexion->close();
?>