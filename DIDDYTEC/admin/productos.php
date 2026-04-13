<?php include "../conexion.php"; ?>

<h2>Agregar Producto</h2>

<form action="guardar_producto.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="id_producto" placeholder="ID del Producto" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="marca" placeholder="Marca" required>
    <input type="number" name="precio" placeholder="Precio" step="0.01" min="0" required>
    <textarea name="descripcion" placeholder="Descripción" required></textarea>
    <input type="file" name="imagen" required>
    <button type="submit">Guardar</button>
</form>

<hr>

<h2>Lista de productos</h2>

<div class="contenedor">

<?php
$sql = "SELECT * FROM productos";
$result = $conexion->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()){
    ?>
        <div class="card">
            <img src="../<?php echo $row['imagen']; ?>" width="120" alt="Producto">
            <h3><?php echo $row['nombre']; ?></h3>
            <p>Marca: <?php echo $row['marca']; ?></p>
            <p><strong>$<?php echo $row['precio']; ?></strong></p>
            
            <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
               onclick="return confirm('¿Seguro que quieres borrar este producto?')"
               style="color:red; font-size: 0.9em; text-decoration: none;">[Eliminar]</a>
        </div>
    <?php 
    } 
}
?>

</div>