<?php
// Usamos LEFT JOIN para que el pedido aparezca aunque el usuario sea 0 o no exista
include "../conexion.php";

if(!isset($_GET['id'])){
    header("Location: index_pedido.php");
    exit();
}

$id_venta = $_GET['id'];

// 1. Consultamos el encabezado de la venta (total, cliente y fecha)
$sql_venta = "SELECT v.*, u.nombre as cliente 
              FROM venta v 
              LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario 
              WHERE v.id_venta = '$id_venta'";
$res_venta = $conexion->query($sql_venta);
$v = $res_venta->fetch_assoc();

// 2. Consultamos los productos detallados (incluyendo nombre e imagen de la tabla productos)
$sql_detalle = "SELECT d.*, p.nombre, p.imagen 
                FROM detalle_venta d 
                JOIN productos p ON d.id_producto = p.id_producto 
                WHERE d.id_venta = '$id_venta'";
$res_detalle = $conexion->query($sql_detalle);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #<?php echo $id_venta; ?></title>
    <link rel="stylesheet" href="../Estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos específicos para esta vista que puedes mover a Estilos.css luego */
        .producto-celda-detalle {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .img-detalle {
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-detalle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #6F4E37;
            padding-bottom: 15px;
        }
        .btn-volver {
            background: #6F4E37;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .total-pedido-detalle {
            margin-top: 20px;
            text-align: right;
            font-size: 1.4rem;
            padding: 15px;
            background: #fdf5e6;
            border-radius: 8px;
            color: #3E2723;
        }
    </style>
</head>
<body class="body-admin">

<div class="contenedor-admin detalle-pedido-modal">
    <header class="header-detalle">
        <div>
            <h1>📦 Detalle del Pedido #<?php echo $id_venta; ?></h1>
            <p>
                <strong>Cliente:</strong> <?php echo $v['cliente'] ? $v['cliente'] : 'Usuario Desconocido'; ?> | 
                <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($v['fecha'])); ?>
            </p>
        </div>
        <a href="index_pedido.php" class="btn-volver">← Volver</a>
    </header>

    <div class="card-detalle-productos">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Precio Unitario</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($d = $res_detalle->fetch_assoc()){ 
                    // Calculamos el precio unitario basándonos en el subtotal guardado y cantidad
                    $precio_u = ($d['cantidad'] > 0) ? ($d['subtotal'] / $d['cantidad']) : 0;
                ?>
                <tr>
                    <td>
                        <div class="producto-celda-detalle">
                            <img src="../<?php echo $d['imagen']; ?>" width="50" height="50" class="img-detalle">
                            <span><?php echo $d['nombre']; ?></span>
                        </div>
                    </td>
                    <td class="text-center">$<?php echo number_format($precio_u, 2); ?></td>
                    <td class="text-center"><?php echo $d['cantidad']; ?></td>
                    <td class="text-center"><strong>$<?php echo number_format($d['subtotal'], 2); ?></strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-pedido-detalle">
            <span>Total del pedido:</span>
            <strong>$<?php echo number_format($v['total'], 2); ?></strong>
        </div>
    </div>
</div>

</body>
</html>