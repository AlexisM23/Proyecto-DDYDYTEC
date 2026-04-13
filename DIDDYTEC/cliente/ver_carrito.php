<?php
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Pedido</title>
    <link rel="stylesheet" href="../Estilos.css">
</head>
<body class="body-cliente">

<div class="contenedor-pedido">
    <header class="header-pedido">
        <h1>🛒 Mi Carrito</h1>
        <a href="index_cliente.php" class="link-volver">← Volver al menú</a>
    </header>

    <?php if(!empty($_SESSION['carrito'])): ?>
        <div class="grid-carrito">
            <div class="seccion-tabla">
                <table class="tabla-pedido">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Subtotal</th>
                            <th></th> </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_pagar = 0;
                        foreach($_SESSION['carrito'] as $id => $cantidad):
                            $sql = "SELECT * FROM productos WHERE id_producto = '$id'";
                            $res = $conexion->query($sql);
                            $p = $res->fetch_assoc();
                            $subtotal = $p['precio'] * $cantidad;
                            $total_pagar += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="producto-celda">
                                    <img src="../<?php echo $p['imagen']; ?>" width="50">
                                    <span><?php echo $p['nombre']; ?></span>
                                </div>
                            </td>
                            <td class="text-center">$<?php echo number_format($p['precio'], 2); ?></td>
                            <td class="text-center"><?php echo $cantidad; ?></td>
                            <td class="text-center"><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                            <td class="text-center">
                                <a href="eliminar_item.php?id=<?php echo $id; ?>" class="btn-eliminar" title="Eliminar producto">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="seccion-resumen">
                <div class="card-resumen">
                    <h3>Resumen de compra</h3>
                    <hr>
                    <div class="fila-resumen total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total_pagar, 2); ?></span>
                    </div>
                    <a href="finalizar_pedido.php" class="btn-confirmar">Confirmar Pedido</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="carrito-vacio">
            <p>No hay productos en tu pedido.</p>
    <?php endif; ?>
</div>

</body>
</html>