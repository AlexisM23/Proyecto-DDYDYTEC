<?php
session_start(); // Iniciamos la sesión para guardar el carrito

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Si el carrito no existe, lo creamos como un arreglo vacío
    if(!isset($_SESSION['carrito'])){
        $_SESSION['carrito'] = array();
    }

    // Si el producto ya está en el carrito, le sumamos 1 a la cantidad
    if(isset($_SESSION['carrito'][$id])){
        $_SESSION['carrito'][$id]++;
    } else {
        // Si es nuevo, lo agregamos con cantidad 1
        $_SESSION['carrito'][$id] = 1;
    }
}

// Regresamos al menú
header("Location: index_cliente.php");
?>