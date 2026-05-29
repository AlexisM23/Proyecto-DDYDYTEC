<?php
// 1. Incluimos la conexión al principio para que esté disponible en todo el archivo
include "../conexion.php"; 

// 2. Iniciamos sesión por seguridad
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - DIDDY TEC</title>
    
    <!-- Librerías Externas -->
    <link rel="stylesheet" href="../Estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- FONT AWESOME PARA LOS ICONOS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Estilos para los botones de la tabla */
        .acciones-flex {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .btn-tabla {
            width: 35px;
            height: 35px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-editar {
            background-color: #f39c12 !important;
            color: white !important;
        }

        .btn-borrar {
            background-color: #e74c3c !important;
            color: white !important;
        }

        .btn-tabla:hover {
            transform: scale(1.1);
            filter: brightness(0.9);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Estilo para las etiquetas de categoría */
        .badge-cat {
            background: #e1e2ff;
            color: #4e54c8;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body class="body-con-fondo"> 

<div class="sidebar">
    <h2>Admin</h2>
    <hr style="border: 0.5px solid rgba(255,255,255,0.1); margin: 15px 0;">
    
    <a href="index_admin.php?vista=productos"><i class="fas fa-box"></i> Productos</a>
    <a href="index_admin.php?vista=usuarios"><i class="fas fa-users"></i> Usuarios</a>
    
    <a href="../pagprincipal.php" style="margin-top: 50px; color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Salir</a>
</div>

<!-- CONTENIDOR PRINCIPAL DINÁMICO -->
<div class="main-content" style="margin-left: 220px; padding: 20px;">

    <?php
    if(isset($_GET['vista'])){
        
        // VISTA DE PRODUCTOS
        if($_GET['vista'] == "productos"){
            ?>
            <!-- FORMULARIO PARA AGREGAR NUEVO PRODUCTO -->
            <div class="contenedor-formulario" style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3><i class="fas fa-plus-circle"></i> Agregar Nuevo Producto</h3>
                <form action="guardar_producto.php" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    
                    <input type="text" name="id_producto" placeholder="ID Producto" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <input type="text" name="nombre" placeholder="Nombre del producto" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <input type="text" name="marca" placeholder="Marca" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">

                    <select name="id_categoria" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Selecciona una Categoría</option>
                        <?php
                        $res_cat = $conexion->query("SELECT * FROM categorias");
                        while($cat = $res_cat->fetch_assoc()){
                            echo "<option value='".$cat['id_categoria']."'>".$cat['nombre_categoria']."</option>";
                        }
                        ?>
                    </select>

                    <input type="number" name="precio" placeholder="Precio" step="0.01" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    
                    <textarea name="descripcion" placeholder="Descripción" style="grid-column: span 2; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    
                    <div style="grid-column: span 2;">
                        <label style="font-weight:600; font-size: 14px;">Imagen del producto:</label>
                        <input type="file" name="imagen" accept="image/*" required>
                    </div>
                    
                    <button type="submit" style="grid-column: span 2; background: #4e54c8; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </form>
            </div>

            <!-- TABLA DE PRODUCTOS EXISTENTES -->
            <div class="contenedor-lista" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h2>Gestión de Productos</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Imagen</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Nombre</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Categoría</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Marca</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Precio</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT p.*, c.nombre_categoria 
                                  FROM productos p 
                                  LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";
                        $resultado = $conexion->query($query);

                        while ($row = $resultado->fetch_assoc()) {
                        ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px; text-align: center;">
                                    <img src="../<?php echo $row['imagen']; ?>" style="width:50px; height:50px; object-fit:cover; border-radius: 6px; border: 1px solid #eee;">
                                </td>
                                <td style="padding: 10px; font-weight: 600;"><?php echo $row['nombre']; ?></td>
                                <td style="padding: 10px;">
                                    <span class="badge-cat"><?php echo $row['nombre_categoria']; ?></span>
                                </td>
                                <td style="padding: 10px; color: #666;"><?php echo $row['marca']; ?></td>
                                <td style="padding: 10px; font-weight: bold; color: #2d3436;">$<?php echo number_format($row['precio'], 2); ?></td>
                                <td style="padding: 10px;">
                                    <div class="acciones-flex">
                                        <!-- BOTÓN EDITAR MODERNO -->
                                        <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn-tabla btn-editar" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <!-- BOTÓN ELIMINAR MODERNO -->
                                        <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                                           class="btn-tabla btn-borrar" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Eliminar este producto?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        // VISTA DE USUARIOS
        if($_GET['vista'] == "usuarios"){
            include "usuarios.php"; 
        }

    } else {
        // PANTALLA DE INICIO
        echo "<div style='background: rgba(255,255,255,0.9); padding: 40px; border-radius: 15px; text-align: center; color: #333;'>";
        echo "<h2>Panel de Control DIDDY TEC</h2>";
        echo "<p>Bienvenido, <b>" . (isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Administrador') . "</b>. Selecciona una opción en el menú de la izquierda.</p>";
        echo "</div>";
    }
    ?>

</div>

</body>
</html>