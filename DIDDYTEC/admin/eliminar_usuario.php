<?php
include "../conexion.php";

// 1. Obtenemos el ID que viene por la URL
if(isset($_GET['id'])){
    // Limpiamos la variable para evitar inyecciones SQL básicos
    $id = $conexion->real_escape_string($_GET['id']);

    // 2. Preparamos la orden de eliminar
    $sql = "DELETE FROM usuarios WHERE id_usuario = '$id'";

    // 3. Ejecutamos y regresamos al panel principal
    if($conexion->query($sql)){
        /* 
           CORRECCIÓN CLAVE: 
           Redirigimos al index_admin.php pasando el parámetro vista=usuarios.
           Esto cargará usuarios.php DENTRO del diseño con el menú lateral.
        */
        header("Location: index_admin.php?vista=usuarios");
        exit(); // Siempre usa exit después de un header para detener la ejecución
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    echo "No se recibió un ID válido.";
}

$conexion->close();
?>