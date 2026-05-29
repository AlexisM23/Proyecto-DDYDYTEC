<?php
session_start();
include "../conexion.php"; 

if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit();
}

$id_cliente = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$nombre_cliente = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos | DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contenedor-cliente {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .badge-cliente {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
        }
        .b-pendiente { background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
        .b-proceso { background-color: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
        .b-listo { background-color: #e8f5e9; color: #1b5e20; border: 1px solid #c8e6c9; animation: pulse 2s infinite; }
        .b-pagado { background-color: #eceff1; color: #37474f; border: 1px solid #cfd8dc; }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="body-cliente" style="background-color: #fcfcfc;">

<div class="contenedor-cliente">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f4f4f4; padding-bottom: 15px;">
        <h2 style="margin: 0; color: #333;"><i class="fas fa-shopping-bag" style="color: #6F4E37;"></i> El Estado de Mis Pedidos</h2>
        <a href="index_cliente.php" style="background-color: #6F4E37; color: white; text-decoration: none; padding: 10px 15px; border-radius: 8px; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Volver a la Tienda
        </a>
    </header>

    <table class="tabla-pedidos">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha / Hora</th>
                <th>Total a Pagar</th>
                <th style="text-align: center;">Estado de tu Orden</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($id_cliente) {
                $sql = "SELECT * FROM venta WHERE id_usuario = '$id_cliente' ORDER BY fecha DESC";
            } else {
                $sql = "SELECT v.* FROM venta v 
                        LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario 
                        WHERE u.nombre = '$nombre_cliente' OR v.id_usuario = '$nombre_cliente'
                        ORDER BY v.fecha DESC";
            }
            
            $res = $conexion->query($sql);

            if ($res && $res->num_rows > 0) {
                while($v = $res->fetch_assoc()){
                    $estado = $v['estado'];
            ?>
            <tr>
                <td><strong>#<?php echo $v['id_venta']; ?></strong></td>
                <td><?php echo date("d/m/Y g:i a", strtotime($v['fecha'])); ?></td>
                <td style="font-weight: 600; color: #2c3e50;">$<?php echo number_format($v['total'], 2); ?></td>
                <td style="text-align: center;">
                    
                    <?php if($estado == 'Pendiente'): ?>
                        <span class="badge-cliente b-pendiente">
                            <i class="fas fa-clock"></i> Recibido (En Espera)
                        </span>
                    <?php elseif($estado == 'En proceso'): ?>
                        <span class="badge-cliente b-proceso">
                            <i class="fas fa-coffee fa-spin"></i> Preparando tu orden...
                        </span>
                    <?php elseif($estado == 'Listo'): ?>
                        <span class="badge-cliente b-listo">
                            <i class="fas fa-bullhorn"></i> ¡Tu pedido está LISTO!
                        </span>
                    <?php elseif($estado == 'Pagado'): ?>
                        <span class="badge-cliente b-pagado">
                            <i class="fas fa-check-double"></i> Entregado y Pagado
                        </span>
                    <?php endif; ?>

                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding: 50px; color: #999;'>
                        <i class='fas fa-mug-hot' style='font-size: 2rem; display:block; margin-bottom:10px;'></i>
                        Aún no has realizado ningún pedido. ¡Anímate a ordenar algo!
                      </td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>