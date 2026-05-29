<?php 
session_start();
include "../conexion.php"; 

if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit();
}

$busqueda = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';
$cat_seleccionada = isset($_GET['cat']) ? $_GET['cat'] : 'todas';

$total_items = 0;
if(isset($_SESSION['carrito'])){
    foreach($_SESSION['carrito'] as $cantidad){ $total_items += $cantidad; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .contenedor-grid-compacto {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .card-compacta {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s;
            border: 1px solid #eee;
        }
        .card-compacta:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .img-compacta {
            height: 150px;
            width: 100%;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .search-container-fix {
            width: 90%;
            max-width: 450px;
            margin: 0 auto 25px auto;
        }
        .search-bar-clean {
            display: flex !important;
            align-items: center !important;
            background: #fff !important;
            border: 2px solid #6F4E37 !important;
            border-radius: 50px !important;
            height: 40px !important;
            padding: 0 15px !important;
        }
        .search-bar-clean input[type="text"] {
            border: none !important;
            margin: 0 !important;
            padding: 0 10px !important;
            flex: 1 !important;
            background: transparent !important;
            color: #333 !important;
            font-size: 14px !important;
            outline: none !important;
        }
    </style>
</head>
<body class="body-cliente" style="background-color: #fcfcfc;">

<div class="barra-superior-cliente" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 5%; background: white; border-bottom: 1px solid #eee;">
    <div style="font-weight: 600; color: #6F4E37;">☕ <?php echo $_SESSION['nombre']; ?></div>
    <div style="display: flex; align-items: center; gap: 15px;">
        
        <a href="mis_pedidos.php" style="text-decoration: none; color: #6F4E37; font-size: 0.85rem; font-weight: 600; border: 1px solid #6F4E37; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
            <i class="fas fa-receipt"></i> Mis Pedidos
        </a>

        <a href="ver_carrito.php" style="text-decoration: none; color: #333; font-size: 0.85rem;">🛒 Carrito (<?php echo $total_items; ?>)</a>
        <a href="../logout.php" style="background: #6F4E37; color: white; padding: 5px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">Salir</a>
    </div>
</div>

<header style="padding: 20px 0; text-align: center;">
    <div class="search-container-fix">
        <form action="index_cliente.php" method="GET" class="search-bar-clean">
            <input type="hidden" name="cat" value="<?php echo htmlspecialchars($cat_seleccionada); ?>">
            <i class="fas fa-search" style="color: #6F4E37; font-size: 0.9rem;"></i>
            <input type="text" name="buscar" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>" autocomplete="off">
            <button type="submit" style="background:none; border:none; cursor:pointer; color:#6F4E37;"><i class="fas fa-chevron-right"></i></button>
        </form>
    </div>

    <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
        <a href="index_cliente.php?cat=todas&buscar=<?php echo urlencode($busqueda); ?>" 
           style="padding: 6px 15px; border-radius: 15px; text-decoration: none; font-size: 0.8rem; border: 1px solid #6F4E37; <?php echo ($cat_seleccionada == 'todas') ? 'background:#6F4E37; color:#fff;' : 'color:#6F4E37;'; ?>">Todas</a>
        <?php
        $res_cat = $conexion->query("SELECT * FROM categorias");
        while($c = $res_cat->fetch_assoc()){
            $active = ($cat_seleccionada == $c['id_categoria']);
            echo "<a href='index_cliente.php?cat=".$c['id_categoria']."&buscar=".urlencode($busqueda)."' 
                       style='padding: 6px 15px; border-radius: 15px; text-decoration: none; font-size: 0.8rem; border: 1px solid #6F4E37; " . ($active ? 'background:#6F4E37; color:#fff;' : 'color:#6F4E37;') . "'>".$c['nombre_categoria']."</a>";
        }
        ?>
    </div>
</header>

<main class="contenedor-grid-compacto">
    <?php
    $sql = "SELECT * FROM productos WHERE 1=1";
    if($cat_seleccionada != 'todas') { $sql .= " AND id_categoria = " . intval($cat_seleccionada); }
    if(!empty($busqueda)) { $sql .= " AND (nombre LIKE '%$busqueda%' OR marca LIKE '%$busqueda%')"; }

    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
        ?>
            <div class="card-compacta">
                <div>
                    <img src="../<?php echo $row['imagen']; ?>" class="img-compacta">
                    <small style="color: #999; font-size: 0.7rem; text-transform: uppercase;"><?php echo $row['marca']; ?></small>
                    <h3 style="margin: 5px 0; font-size: 1rem; color: #333;"><?php echo $row['nombre']; ?></h3>
                </div>
                
                <div style="margin-top: 10px;">
                    <p style="font-weight: bold; color: #2ecc71; font-size: 1.1rem; margin-bottom: 8px;">$<?php echo number_format($row['precio'], 2); ?></p>
                    <a href="agregar_carrito.php?id=<?php echo $row['id_producto']; ?>" 
                       style="background: #6F4E37; color: white; display: block; padding: 8px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: bold;">
                       + Agregar
                    </a>
                </div>
            </div>
        <?php 
        } 
    } else {
        echo "<div style='grid-column: 1/-1; text-align: center; color: #bbb; font-size: 0.9rem;'>No hay resultados.</div>";
    }
    ?>
</main>

</body>
</html>