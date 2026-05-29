<?php 
// 1. Esto falta en tu imagen: realizar la consulta
$sql = "SELECT * FROM productos";
$result = $conexion->query($sql); 
?>

<table class="tabla-admin">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Precio</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <!-- Línea 57 de tu imagen corregida -->
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><img src="../<?php echo $row['imagen']; ?>" width="50" style="border-radius:5px;"></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><span class="badge-categoria"><?php echo $row['categoria']; ?></span></td>
            <td><?php echo $row['marca']; ?></td>
            <td><strong>$<?php echo $row['precio']; ?></strong></td>
            <td class="text-center">
                <td class="text-center">
    <div class="acciones-flex">
        <!-- Sin texto, solo la clase y el icono -->
        <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn-tabla btn-editar">
            <i class="fas fa-pen"></i>
        </a>

        <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
           onclick="return confirm('¿Eliminar?')" 
           class="btn-tabla btn-borrar">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>