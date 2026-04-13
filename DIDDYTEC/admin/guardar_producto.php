<?php
include "../conexion.php";

$id_producto = $_POST['id_producto'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$marca = $_POST['marca'];
$descripcion = $_POST['descripcion'];

// IMAGEN
$imagen = $_FILES['imagen']['name'];
$tmp = $_FILES['imagen']['tmp_name'];

// ruta donde se guardará
$ruta = "imagenes/" . $imagen;

// mover imagen a carpeta
move_uploaded_file($tmp, "../" . $ruta);

// guardar en BD
$sql = "INSERT INTO productos (id_producto, nombre, precio, imagen, marca, descripcion)
VALUES ('$id_producto', '$nombre', '$precio', '$ruta', '$marca', '$descripcion')";

$conexion->query($sql);

header("Location: index_admin.php?vista=productos");
$conexion->close();
?>