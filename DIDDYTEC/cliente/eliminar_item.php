<?php
session_start();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Si el producto existe en el carrito, lo eliminamos
    if(isset($_SESSION['carrito'][$id])){
        unset($_SESSION['carrito'][$id]);
    }
}

// Regresamos a la página del carrito
header("Location: ver_carrito.php");
?>