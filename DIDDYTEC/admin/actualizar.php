<?php
include "../conexion.php";

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];

$sql = "UPDATE productos 
SET nombre='$nombre', descripcion='$descripcion', precio='$precio'
WHERE id=$id";

$conexion->query($sql);

header("Location: index_admin.php");
?>