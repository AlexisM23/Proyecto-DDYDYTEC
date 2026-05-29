<?php 
// 1. Iniciamos sesión y conexión
session_start();
include "../conexion.php"; 

// 2. Filtro de seguridad
if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Pedido - DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <!-- FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="body-cliente">

<div class="contenedor-pedido">
    <header class="header-pedido" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0;">
        <h1><i class="fas fa-shopping-cart"></i> Mi Carrito</h1>
        <a href="index_cliente.php" class="link-volver" style="text-decoration: none; color: #6F4E37; font-weight: bold;">
            <i class="fas fa-arrow-left"></i> Volver al menú
        </a>
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
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_pagar = 0;
                        foreach($_SESSION['carrito'] as $id => $cantidad):
                            // Sanitización básica del ID
                            $id_safe = $conexion->real_escape_string($id);
                            $sql = "SELECT * FROM productos WHERE id_producto = '$id_safe'";
                            $res = $conexion->query($sql);
                            if($res && $p = $res->fetch_assoc()):
                                $subtotal = $p['precio'] * $cantidad;
                                $total_pagar += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="producto-celda" style="display: flex; align-items: center; gap: 10px;">
                                    <img src="../<?php echo $p['imagen']; ?>" width="50" style="border-radius: 5px;">
                                    <span><?php echo $p['nombre']; ?></span>
                                </div>
                            </td>
                            <td class="text-center">$<?php echo number_format($p['precio'], 2); ?></td>
                            <td class="text-center"><?php echo $cantidad; ?></td>
                            <td class="text-center"><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                            <td class="text-center">
                                <a href="eliminar_item.php?id=<?php echo $id; ?>" class="btn-eliminar-pro" title="Eliminar producto" style="color: #e74c3c; font-size: 1.2rem;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endif;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="seccion-resumen">
                <div class="card-resumen" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; color: #333;">Resumen de compra</h3>
                    <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                    
                    <div class="fila-resumen total" style="display: flex; justify-content: space-between; font-size: 1.3rem; margin-bottom: 25px;">
                        <strong>Total:</strong>
                        <strong style="color: #27ae60;">$<?php echo number_format($total_pagar, 2); ?></strong>
                    </div>

                    <!-- BOTÓN CORREGIDO Y MEJORADO -->
                    <div style="text-align: center;">
                        <a href="finalizar_pedido.php" class="btn-confirmar">
                            <i class="fas fa-check-circle"></i> Confirmar Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="carrito-vacio" style="text-align: center; padding: 80px 20px;">
            <i class="fas fa-shopping-basket" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
            <p style="font-size: 1.2rem; color: #666;">No hay productos en tu pedido actualmente.</p>
            <a href="index_cliente.php" class="btn-agregar" style="display: inline-block; margin-top: 20px; text-decoration: none;">Ir al Menú</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>