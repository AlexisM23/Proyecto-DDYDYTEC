<?php
include "../conexion.php";

// 1. Validar que recibimos el ID de la venta
if(isset($_GET['id'])){
    $id_venta = $_GET['id'];

    // 2. Actualizar el estado de 'Pendiente' a 'Pagado'
    $sql = "UPDATE venta SET estado = 'Pagado' WHERE id_venta = '$id_venta'";
    
    if($conexion->query($sql)){
        // 3. Si todo sale bien, regresar al panel de pedidos
        echo "<script>
                alert('Pedido #$id_venta marcado como pagado.');
                window.location.href = 'index_pedido.php';
              </script>";
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
} else {
    header("Location: index_pedido.php");
}
?>