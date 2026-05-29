<?php
include "../conexion.php";

if (isset($_GET['id']) && isset($_GET['nuevo_estado'])) {
    // Limpiamos los parámetros recibidos por la URL
    $id_venta = $conexion->real_escape_string($_GET['id']);
    $nuevo_estado = $conexion->real_escape_string($_GET['nuevo_estado']);

    // Validar que el estado sea uno de los permitidos para evitar inyecciones maliciosas
    $estados_validos = ['En proceso', 'Listo', 'Pagado'];
    
    if (in_array($nuevo_estado, $estados_validos)) {
        
        // ¡CORRECCIÓN AQUÍ!: Cambiado de 'pedidos' a tu tabla real 'venta' y llave 'id_venta'
        $sql = "UPDATE venta SET estado = '$nuevo_estado' WHERE id_venta = '$id_venta'";
        
        if ($conexion->query($sql)) {
            // Te redirige de vuelta a tu panel de control de caja de forma limpia
            header("Location: index_pedido.php"); 
            exit();
        } else {
            echo "Error al actualizar el estado en la base de datos: " . $conexion->error;
        }
        
    } else {
        echo "Error: El estado enviado no es válido.";
    }
} else {
    // Si se accede al archivo sin variables en la URL, regresa al panel
    header("Location: index_pedido.php");
    exit();
}
?>