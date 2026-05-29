<?php
session_start();
include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto  = $conexion->real_escape_string($_POST['id_original']);
    $nombre       = $conexion->real_escape_string($_POST['nombre']);
    $precio       = $conexion->real_escape_string($_POST['precio']);
    $marca        = $conexion->real_escape_string($_POST['marca']); // <- Captura la marca
    $id_categoria = $conexion->real_escape_string($_POST['id_categoria']);
    $descripcion  = $conexion->real_escape_string($_POST['descripcion']);

    // Recuperar imagen por defecto
    $sql_actual = "SELECT imagen FROM productos WHERE id_producto = '$id_producto'";
    $res_actual = $conexion->query($sql_actual);
    $prod_actual = $res_actual->fetch_assoc();
    $ruta_imagen = $prod_actual['imagen'];

    // Manejo de nueva imagen si se sube
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == 0) {
        $nombre_img = $_FILES['nueva_imagen']['name'];
        $temporal   = $_FILES['nueva_imagen']['tmp_name'];
        $extension  = pathinfo($nombre_img, PATHINFO_EXTENSION);
        $nuevo_nombre_img = uniqid("prod_") . "." . $extension;
        
        if (move_uploaded_file($temporal, "../imagenes/" . $nuevo_nombre_img)) {
            $ruta_imagen = "imagenes/" . $nuevo_nombre_img;
        }
    }

    // UPDATE modificado incluyendo el campo marca
    $sql_update = "UPDATE productos SET 
                    nombre = '$nombre', 
                    precio = '$precio', 
                    marca = '$marca', 
                    id_categoria = '$id_categoria', 
                    descripcion = '$descripcion', 
                    imagen = '$ruta_imagen' 
                   WHERE id_producto = '$id_producto'";

    if ($conexion->query($sql_update)) {
        echo "<script>alert('¡Producto actualizado correctamente!'); window.location.href = 'index_admin.php?vista=productos';</script>";
    } else {
        echo "<script>alert('Error al guardar: " . $conexion->error . "'); window.history.back();</script>";
    }
}
?>