<?php include "../conexion.php"; ?>

<h2>Alta de Usuario</h2>

<form action="./guardar_usuarios.php" method="POST">
    <input type="number" name="id_usuario" placeholder="ID de Usuario" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    

    <select name="tipo">
        <option value="admin">Admin</option>
        <option value="caja">Caja</option>
        <option value="cliente">Cliente</option>
    </select>

    <button type="submit">Guardar</button>
</form>

<hr>

<h2>Usuarios</h2>

<?php
$sql = "SELECT * FROM usuarios";
$result = $conexion->query($sql);

if(!$result){
    die("Error en la consulta: " . $conexion->error);
}


while($row = $result->fetch_assoc()){
    echo "<p>";
    echo "<strong>ID: " . $row['id_usuario'] . "</strong> - " . $row['nombre'] . " (" . $row['tipo_usuario'] . ") ";
    
    // Agregamos el enlace para eliminar pasando el ID por la URL
    echo "<a href='eliminar_usuario.php?id=" . $row['id_usuario'] . "' 
          onclick='return confirm(\"¿Estás seguro de eliminar a este usuario?\")' 
          style='color:red; text-decoration:none; margin-left:10px;'>[Eliminar]</a>";
    
    echo "</p>";
}
?>