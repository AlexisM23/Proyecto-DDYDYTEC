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
        <h3 style="margin: 0;">☕ Panel de Pedidos Pendientes</h3>
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
            // Consulta para obtener los pedidos pendientes con nombre de usuario
            $sql = "SELECT v.*, u.nombre as cliente 
                    FROM venta v 
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario 
                    WHERE v.estado = 'Pendiente' 
                    ORDER BY v.fecha DESC";
            
            $res = $conexion->query($sql);
            
            if ($res && $res->num_rows > 0) {
                while($v = $res->fetch_assoc()){
                    $nombre_cliente = $v['cliente'] ? $v['cliente'] : "Usuario Desconocido";
            ?>
            <tr>
                <td><strong>#<?php echo $v['id_venta']; ?></strong></td>
                <td><?php echo $nombre_cliente; ?></td>
                <td>$<?php echo number_format($v['total'], 2); ?></td>
                <td><span class="estado-pendiente">● <?php echo $v['estado']; ?></span></td>
                <td style="text-align: center;">
                    <a href="detalle_pedido.php?id=<?php echo $v['id_venta']; ?>" class="btn-accion ver-detalle">
                        👁️ Ver Detalle
                    </a>
                    
                    <a href="completar_pedido.php?id=<?php echo $v['id_venta']; ?>" class="btn-accion marcar-pagado">
                        ✅ Marcar Pagado
                    </a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding: 40px;'>No hay pedidos pendientes en este momento. ☕</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>