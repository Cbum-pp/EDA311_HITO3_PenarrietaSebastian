<?php
require_once 'conexion.php';
$mensaje = "";

$id_editar = "";
$nombre_edit = "";
$categoria_edit = "";
$precio_edit = "";
$stock_edit = "";
$modo_edicion = false;

if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    try {
        $sql_delete = "DELETE FROM productos WHERE id_producto = :id";
        $stmt_del = $pdo->prepare($sql_delete);
        $stmt_del->execute([':id' => $id_eliminar]);
        header("Location: productos.php"); 
        exit();
    } catch (PDOException $e) {
        $mensaje = "<p style='color: red;'>Error al eliminar: No puedes borrar un producto si ya tiene pedidos asociados.</p>";
    }
}

if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $modo_edicion = true;
    
    $sql_edit = "SELECT * FROM productos WHERE id_producto = :id";
    $stmt_edit = $pdo->prepare($sql_edit);
    $stmt_edit->execute([':id' => $id_editar]);
    $producto_a_editar = $stmt_edit->fetch();
    
    if ($producto_a_editar) {
        $nombre_edit = $producto_a_editar['nombre'];
        $categoria_edit = $producto_a_editar['categoria'];
        $precio_edit = $producto_a_editar['precio'];
        $stock_edit = $producto_a_editar['stock'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    if ($_POST['accion'] == 'Registrar') {
        // Modo Crear
        try {
            $sql = "INSERT INTO productos (nombre, categoria, precio, stock) VALUES (:nombre, :categoria, :precio, :stock)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre, ':categoria' => $categoria, ':precio' => $precio, ':stock' => $stock]);
            $mensaje = "<p style='color: green;'>Producto registrado correctamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al registrar: " . $e->getMessage() . "</p>";
        }
    } elseif ($_POST['accion'] == 'Actualizar') {
        // Modo Editar
        $id_actualizar = $_POST['id_producto'];
        try {
            $sql_update = "UPDATE productos SET nombre = :nombre, categoria = :categoria, precio = :precio, stock = :stock WHERE id_producto = :id";
            $stmt_upd = $pdo->prepare($sql_update);
            $stmt_upd->execute([':nombre' => $nombre, ':categoria' => $categoria, ':precio' => $precio, ':stock' => $stock, ':id' => $id_actualizar]);
            $mensaje = "<p style='color: green;'>Producto actualizado correctamente.</p>";
            // Resetear variables para que el formulario vuelva a modo "Registrar"
            $modo_edicion = false;
            $nombre_edit = $categoria_edit = $precio_edit = $stock_edit = "";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al actualizar: " . $e->getMessage() . "</p>";
        }
    }
}

$sql_select = "SELECT * FROM productos";
$stmt_select = $pdo->query($sql_select);
$productos = $stmt_select->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; }
        .btn-eliminar { color: red; text-decoration: none; font-weight: bold; }
        .btn-editar { color: blue; text-decoration: none; font-weight: bold; margin-right: 15px; }
        .btn-volver { display: inline-block; margin-bottom: 20px; padding: 10px; background: #ddd; text-decoration: none; color: black; }
        .titulo-formulario { color: <?php echo $modo_edicion ? 'blue' : 'black'; ?>; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-volver">← Volver al Inicio</a>
    <h1>Gestión de Productos</h1>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <h2 class="titulo-formulario">
            <?php echo $modo_edicion ? 'Modificar Producto Seleccionado' : 'Registrar Nuevo Producto'; ?>
        </h2>
        
        <form method="POST" action="productos.php">
            <!-- Campo oculto para pasar el ID cuando estamos editando -->
            <?php if ($modo_edicion): ?>
                <input type="hidden" name="id_producto" value="<?php echo $id_editar; ?>">
            <?php endif; ?>

            <label for="nombre">Nombre del Producto:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre_edit); ?>" required><br><br>
            
            <label for="categoria">Categoría:</label><br>
            <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($categoria_edit); ?>" required><br><br>

            <label for="precio">Precio:</label><br>
            <input type="number" step="0.01" min="0" id="precio" name="precio" value="<?php echo $precio_edit; ?>" required><br><br>

            <label for="stock">Stock (Cantidad):</label><br>
            <input type="number" min="0" id="stock" name="stock" value="<?php echo $stock_edit; ?>" required><br><br>
            
            <?php if ($modo_edicion): ?>
                <input type="submit" name="accion" value="Actualizar" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                <a href="productos.php" style="margin-left: 10px; text-decoration: none; color: gray;">Cancelar edición</a>
            <?php else: ?>
                <input type="submit" name="accion" value="Registrar" style="padding: 10px 20px; cursor: pointer;">
            <?php endif; ?>
        </form>
    </div>

    <h2>Listado de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo $producto['id_producto']; ?></td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td>
                    <!-- Al hacer clic, enviamos el ID por la URL para activar el modo edición -->
                    <a href="productos.php?editar=<?php echo $producto['id_producto']; ?>" class="btn-editar">Editar</a>
                    <a href="productos.php?eliminar=<?php echo $producto['id_producto']; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (count($productos) == 0): ?>
                <tr><td colspan="6">No hay productos registrados en el inventario.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
