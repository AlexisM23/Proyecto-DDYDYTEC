<?php 
// 1. Iniciamos sesión y conexión al puro principio
session_start();
include "../conexion.php"; 

// 2. Filtro de seguridad: Si no está logueado, mandarlo al login
if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit();
}

// 3. Conteo de productos en el carrito
$total_items = 0;
if(isset($_SESSION['carrito'])){
    foreach($_SESSION['carrito'] as $cantidad){
        $total_items += $cantidad;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="body-cliente">

<div class="barra-superior-cliente" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 5%; background: rgba(255,255,255,0.9); position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    
    <div class="usuario-info">
        <span>☕ Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></span>
    </div>

    <div class="acciones-nav" style="display: flex; gap: 15px; align-items: center;">
        <div class="barra-carrito" style="margin: 0; padding: 5px 15px; background: #fdf5e6; border-radius: 20px;">
            <span>🛒 <strong><?php echo $total_items; ?></strong></span>
            <a href="ver_carrito.php" class="btn-ver-carrito" style="margin-left: 10px; text-decoration: none; color: #6F4E37; font-weight: bold;">Ver Pedido</a>
        </div>

        <a href="../logout.php" class="btn-logout" style="background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>
</div>

<header class="header-cliente" style="text-align: center; padding: 40px 0;">
    <h1 class="titulo-menu">Nuestro Menú</h1>
    <p class="subtitulo-menu">Calidad y sabor en cada producto</p>
</header>

<div class="contenedor-grid">
    <?php
    $sql = "SELECT * FROM productos";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
        ?>
            <div class="producto-card">
                <div class="imagen-recipiente">
                    <img src="../<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                </div>
                
                <div class="info-producto">
                    <span class="marca-badge"><?php echo $row['marca']; ?></span>
                    <h3><?php echo $row['nombre']; ?></h3>
                    <p class="descripcion-corta"><?php echo $row['descripcion']; ?></p>
                    
                    <div class="fila-precio-accion">
                        <p class="precio-destacado">$<?php echo $row['precio']; ?></p>
                        <a href="agregar_carrito.php?id=<?php echo $row['id_producto']; ?>" class="btn-agregar">Agregar al carrito</a>
                    </div>
                </div>
            </div>
        <?php 
        } 
    } else {
        echo "<p class='sin-productos' style='grid-column: 1/-1; text-align: center; padding: 50px;'>Por el momento no hay productos disponibles. ☕</p>";
    }
    ?>
</div>

</body>
</html>