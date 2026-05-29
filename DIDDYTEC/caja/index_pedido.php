<?php
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Caja - DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="body-admin">

<div class="contenedor-caja">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">☕ Panel de Pedidos de Caja</h3>
        <a href="../login.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </header>
    
    <table class="tabla-pedidos">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Modificado: Ahora trae los pedidos Pendientes, En proceso y Listos (oculta solo los ya cobrados/Pagados)
            $sql = "SELECT v.*, u.nombre as cliente 
                    FROM venta v 
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario 
                    WHERE v.estado IN ('Pendiente', 'En proceso', 'Listo') 
                    ORDER BY v.fecha DESC";
            
            $res = $conexion->query($sql);
            
            if ($res && $res->num_rows > 0) {
                while($v = $res->fetch_assoc()){
                    $nombre_cliente = $v['cliente'] ? $v['cliente'] : "Usuario Desconocido";
                    $estado_actual = $v['estado'];
            ?>
            <tr>
                <td><strong>#<?php echo $v['id_venta']; ?></strong></td>
                <td><?php echo htmlspecialchars($nombre_cliente); ?></td>
                <td>$<?php echo number_format($v['total'], 2); ?></td>
                <td>
                    <?php if($estado_actual == 'Pendiente'): ?>
                        <span class="estado-pendiente" style="background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2;">● Pendiente</span>
                    <?php elseif($estado_actual == 'En proceso'): ?>
                        <span class="estado-pendiente" style="background-color: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb;">⚙️ En proceso</span>
                    <?php elseif($estado_actual == 'Listo'): ?>
                        <span class="estado-pendiente" style="background-color: #e8f5e9; color: #1b5e20; border: 1px solid #c8e6c9;">☕ ¡Listo!</span>
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center; width: 100%;">
                        
                        <a href="detalle_pedido.php?id=<?php echo $v['id_venta']; ?>" class="btn-accion ver-detalle">
                            👁️ Detalle
                        </a>
                        
                        <?php if($estado_actual == 'Pendiente'): ?>
                            <a href="cambiar_estado.php?id=<?php echo $v['id_venta']; ?>&nuevo_estado=En proceso" class="btn-accion" style="background-color: #2980b9; color: white;">
                                ⏳ En Proceso
                            </a>
                        <?php endif; ?>

                        <?php if($estado_actual == 'Pendiente' || $estado_actual == 'En proceso'): ?>
                            <a href="cambiar_estado.php?id=<?php echo $v['id_venta']; ?>&nuevo_estado=Listo" class="btn-accion" style="background-color: #f39c12; color: white;">
                                📦 Listo
                            </a>
                        <?php endif; ?>

                        <a href="cambiar_estado.php?id=<?php echo $v['id_venta']; ?>&nuevo_estado=Pagado" class="btn-accion marcar-pagado">
                            ✅ Pagado
                        </a>
                        
                    </div>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding: 40px;'>No hay pedidos pendientes o en preparación en este momento. ☕</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>