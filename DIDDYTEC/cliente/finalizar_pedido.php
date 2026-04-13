<?php
session_start();
include "../conexion.php";

// 1. Verificación de Seguridad: ¿Hay un usuario logueado?
if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == 0){
    echo "<script>
            alert('Error: Debes iniciar sesión para realizar un pedido.');
            window.location.href = '../login.php'; 
          </script>";
    exit();
}

if(!empty($_SESSION['carrito'])){
    $id_usuario = $_SESSION['id_usuario']; 
    $total = 0;

    foreach($_SESSION['carrito'] as $id => $cantidad){
        $sql_p = "SELECT precio FROM productos WHERE id_producto = '$id'";
        $res_p = $conexion->query($sql_p);
        $p = $res_p->fetch_assoc();
        $total += ($p['precio'] * $cantidad);
    }

    // 2. Insertar con la fecha actual (NOW()) para que el cajero sepa cuándo se pidió
    $sql_v = "INSERT INTO venta (id_usuario, total, estado, fecha) 
              VALUES ('$id_usuario', '$total', 'Pendiente', NOW())";
    
    if($conexion->query($sql_v)){
        $id_venta = $conexion->insert_id; 

        foreach($_SESSION['carrito'] as $id => $cantidad){
            $sql_p = "SELECT precio FROM productos WHERE id_producto = '$id'";
            $res_p = $conexion->query($sql_p);
            $p = $res_p->fetch_assoc();
            $subtotal = $p['precio'] * $cantidad;

            $sql_d = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) 
                      VALUES ('$id_venta', '$id', '$cantidad', '$subtotal')";
            $conexion->query($sql_d);
        }

        unset($_SESSION['carrito']);
        echo "<script>
                alert('Pedido #$id_venta enviado a caja con éxito.');
                window.location.href = 'index_cliente.php'; 
              </script>";
    } else {
        echo "Error al guardar el pedido: " . $conexion->error;
    }
} else {
    header("Location: index_cliente.php");
}
?>