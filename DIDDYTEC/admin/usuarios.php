<?php 

// 1. CONEXIÓN A LA BASE DE DATOS
include "../conexion.php"; 
?>
<!-- Enlace para que se vean los iconos de Editar y Eliminar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Estilos internos rápidos para mejorar el formulario sin tocar el CSS principal aún -->
<style>
    .form-alta { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .form-alta input, .form-alta select { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
    .btn-guardar { width: 100%; background: #4834d4; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; }
    .btn-guardar:hover { background: #686de0; }
    
    /* Clases para los badges de roles */
    .badge { padding: 4px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize; }
    .admin { background: #ffebee; color: #c62828; }
    .caja { background: #e3f2fd; color: #1565c0; }
    .cliente { background: #f1f8e9; color: #2e7d32; }
</style>

<div class="contenedor-admin-usuarios">
    <h2 class="titulo-seccion"><i class="fas fa-user-plus"></i> Alta de Usuario</h2>

    <div class="form-alta">
        <form action="./guardar_usuarios.php" method="POST">
            <input type="number" name="id_usuario" placeholder="ID de Usuario (Cédula o Código)" required>
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="password" name="password" placeholder="Contraseña de acceso" required>
            
            <select name="tipo">
                <option value="admin">Administrador</option>
                <option value="caja">Personal de Caja</option>
                <option value="cliente" selected>Cliente</option>
            </select>

            <button type="submit" class="btn-guardar">Guardar Usuario</button>
        </form>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

    <h2 class="titulo-seccion"><i class="fas fa-users"></i> Usuarios Registrados</h2>

    <div class="tabla-responsiva">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Rol / Privilegio</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 3. CONSULTA DE LECTURA
                $sql = "SELECT * FROM usuarios";
                $result = $conexion->query($sql);

                if(!$result){
                    echo "<tr><td colspan='4'>Error: " . $conexion->error . "</td></tr>";
                } else {
                    // 4. BUCLE PARA LISTAR USUARIOS EN TABLA
                    while($row = $result->fetch_assoc()){
                        // Ajuste automático del color del badge según el tipo
                        // Nota: He usado 'tipo_usuario' que es como aparece en tu código original
                        $rol = $row['tipo_usuario']; 
                        ?>
                        <tr>
                            <td><strong><?php echo $row['id_usuario']; ?></strong></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>
                                <span class="badge <?php echo $rol; ?>">
                                    <?php echo $rol; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Botones de acción con iconos -->
                                <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn-accion edit" title="Editar">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                
                                <a href="eliminar_usuario.php?id=<?php echo $row['id_usuario']; ?>" 
                                   onclick="return confirm('¿Estás seguro de eliminar a <?php echo $row['nombre']; ?>?')" 
                                   class="btn-accion delete" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>