<?php 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $sql = "SELECT * FROM productos";
    $result = $conexion->query($sql);

    while($row = $result->fetch_assoc()){ 
?>
    <div class="card">
        <img src="../<?php echo $row['imagen']; ?>" width="120">
        <h3><?php echo $row['nombre']; ?></h3>
        <p>$<?php echo $row['precio']; ?></p>
    </div>
<?php 
    }

} catch (mysqli_sql_exception $e) {
    echo "<p style='color:red;'>Error al obtener productos: " . $e->getMessage() . "</p>";
}
?>