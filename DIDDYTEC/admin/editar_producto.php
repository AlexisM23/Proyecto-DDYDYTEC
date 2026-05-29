<?php
include "../conexion.php";
session_start();

// 1. Obtener los datos del producto de forma segura
$prod = null;
if (isset($_GET['id'])) {
    $id = $conexion->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM productos WHERE id_producto = '$id'";
    $res = $conexion->query($sql);
    if ($res->num_rows > 0) {
        $prod = $res->fetch_assoc();
    }
}

// Si no se encuentra el producto, redirigir
if (!$prod) {
    header("Location: index_admin.php?vista=productos");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Producto | DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container-edit {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        .btn-update {
            background: #4e54c8;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-update:hover { background: #3f44a3; transform: translateY(-2px); }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-back:hover { color: #e74c3c; }
        .current-img {
            text-align: center;
            padding: 10px;
            border: 1px dashed #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="body-con-fondo">

<div class="container-edit">
    <h2 style="text-align: center; color: #4e54c8; margin-top: 0;">
        <i class="fas fa-box-open"></i> Modificar Producto
    </h2>
    <p style="text-align: center; color: #888; font-size: 0.9em;">ID Producto: <?php echo $prod['id_producto']; ?></p>
    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 25px;">

    <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_original" value="<?php echo $prod['id_producto']; ?>">

        <div class="form-group">
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($prod['nombre']); ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Precio ($):</label>
                <input type="number" name="precio" step="0.01" class="form-control" value="<?php echo $prod['precio']; ?>" required>
            </div>
            <div class="form-group">
                <label>Marca:</label>
                <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($prod['marca']); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Categoría:</label>
            <select name="id_categoria" class="form-control" required>
                <?php
                $res_cat = $conexion->query("SELECT * FROM categorias");
                while($cat = $res_cat->fetch_assoc()){
                    $selected = ($cat['id_categoria'] == $prod['id_categoria']) ? "selected" : "";
                    echo "<option value='".$cat['id_categoria']."' $selected>".$cat['nombre_categoria']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($prod['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Imagen del Producto:</label>
            <div class="current-img">
                <p style="font-size: 12px; color: #999; margin-bottom: 5px;">Imagen actual:</p>
                <img src="../<?php echo $prod['imagen']; ?>" width="80" style="border-radius: 5px;">
            </div>
            <input type="file" name="nueva_imagen" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn-update">
            <i class="fas fa-sync-alt"></i> Actualizar Producto
        </button>

        <a href="index_admin.php?vista=productos" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver sin cambios
        </a>
    </form>
</div>

</body>
</html>