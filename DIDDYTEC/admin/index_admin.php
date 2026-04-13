<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - DIDDY TEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="body-con-fondo"> <div class="sidebar">
    <h2>Admin</h2>
    <hr style="border: 0.5px solid rgba(255,255,255,0.1); margin: 15px 0;">
    
    <a href="?vista=productos">📦 Productos</a>
    <a href="?vista=usuarios">👤 Usuarios</a>
    
    <a href="../pagprincipal.php">🚪 Salir</a>
</div>

<div class="contenido"> <div class="header-menu" style="margin-bottom: 30px;">
        <?php
        if(isset($_GET['vista'])){
            if($_GET['vista'] == "productos"){
                echo "<h1>Gestión de Inventario</h1>";
            } elseif($_GET['vista'] == "usuarios") {
                echo "<h1>Control de Usuarios</h1>";
            }
        } else {
            echo "<h1>Bienvenido, Admin</h1>";
            echo "<p>Usa el menú lateral para gestionar la cafetería.</p>";
        }
        ?>
    </div>

    <?php
    if(isset($_GET['vista'])){
        
        if($_GET['vista'] == "productos"){
            include "productos.php";
        }

        if($_GET['vista'] == "usuarios"){
            include "usuarios.php";
        }

    } else {
        // Cuadro de bienvenida opcional
        echo "<div style='background: rgba(255,255,255,0.9); padding: 40px; border-radius: 15px; text-align: center; color: #333;'>";
        echo "<h2>Panel de Control DIDDY TEC</h2>";
        echo "<p>Desde aquí puedes agregar nuevos productos, cambiar precios o gestionar los accesos al sistema.</p>";
        echo "</div>";
    }
    ?>

</div>

</body>
</html>