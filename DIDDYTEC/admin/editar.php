<?php
include "../conexion.php";

$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id=$id";
$result = $conexion->query($sql);
$row = $result->fetch_assoc();
?>

<form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    
    <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>">
    <textarea name="descripcion"><?php echo $row['descripcion']; ?></textarea>
    <input type="number" step="0.01" name="precio" value="<?php echo $row['precio']; ?>">
    
    <button type="submit">Actualizar</button>
</form>