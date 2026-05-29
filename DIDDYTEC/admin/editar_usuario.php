<?php 
// 1. CONEXIÓN A LA BASE DE DATOS
include "../conexion.php"; 

// Obtener el ID de la URL y sanitizarlo para seguridad
$id = $conexion->real_escape_string($_GET['id']);
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$id'";
$result = $conexion->query($sql);
$row = $result->fetch_assoc();

// Si el usuario no existe, redirigir al panel
if (!$row) {
    header("Location: index_admin.php?vista=usuarios");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - DIDDYTEC</title>
    <link rel="stylesheet" href="../Estilos.css">
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contenedor-formulario {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 { color: #333; margin-bottom: 25px; text-align: center; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* Evita que el input se salga del contenedor */
        }
        .btn-actualizar {
            width: 100%;
            background: #4834d4;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-actualizar:hover { background: #686de0; }
        
        .btn-cancelar {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #e74c3c;
            text-decoration: none;
            font-weight: 600;
            padding: 10px;
            border: 1px solid #e74c3c;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-cancelar:hover {
            background: #fdedec;
        }
    </style>
</head>
<body style="background-color: #f4f7f6;">

    <div class="contenedor-formulario">
        <h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>
        
        <form action="actualizar_usuario.php" method="POST">
            <!-- ID oculto para la consulta UPDATE -->
            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">

            <label>Nombre Completo:</label>
            <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required>

            <label>Tipo de Usuario (Rol):</label>
            <select name="tipo_usuario">
                <option value="admin" <?php if($row['tipo_usuario'] == 'admin') echo 'selected'; ?>>Administrador</option>
                <option value="cliente" <?php if($row['tipo_usuario'] == 'cliente') echo 'selected'; ?>>Cliente</option>
                <option value="caja" <?php if($row['tipo_usuario'] == 'caja') echo 'selected'; ?>>Personal de Caja</option>
            </select>

            <label>Nueva Contraseña:</label>
            <input type="password" name="password" placeholder="Dejar en blanco para no cambiar">

            <button type="submit" class="btn-actualizar">
                <i class="fas fa-save"></i> Actualizar Usuario
            </button>

            <!-- MEJORA: Retorno al index_admin para no perder el diseño -->
            <a href="index_admin.php?vista=usuarios" class="btn-cancelar">
                <i class="fas fa-times"></i> Cancelar y Regresar
            </a>
        </form>
    </div>

</body>
</html>