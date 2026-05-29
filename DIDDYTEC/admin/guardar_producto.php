<?php
include "../conexion.php";

// 1. Recolección de datos
$id_producto  = $_POST['id_producto'];
$nombre       = $_POST['nombre'];
$precio       = $_POST['precio'];
$marca        = $_POST['marca'];
$descripcion  = $_POST['descripcion'];
$id_categoria = $_POST['id_categoria']; // Capturamos el ID de la categoría seleccionada

// 2. Manejo de la IMAGEN
$imagen = $_FILES['imagen']['name'];
$tmp    = $_FILES['imagen']['tmp_name'];

// Generar un nombre único para la imagen
$nombre_imagen_final = time() . "_" . $imagen;
$ruta_carpeta = "../imagenes/" . $nombre_imagen_final;
$ruta_db = "imagenes/" . $nombre_imagen_final; 

if (move_uploaded_file($tmp, $ruta_carpeta)) {
    // 3. Guardar en BD incluyendo el campo id_categoria
    $sql = "INSERT INTO productos (id_producto, nombre, precio, imagen, marca, descripcion, id_categoria) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        // "isssssi" -> el último 'i' corresponde al id_categoria (entero)
        $stmt->bind_param("isssssi", $id_producto, $nombre, $precio, $ruta_db, $marca, $descripcion, $id_categoria);
        
        if($stmt->execute()){
            header("Location: index_admin.php?vista=productos");
            exit();
        } else {
            echo "Error al guardar en base de datos: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Error al subir la imagen al servidor.";
}

$conexion->close();
?>