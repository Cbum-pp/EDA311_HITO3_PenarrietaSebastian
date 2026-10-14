<?php
require_once 'conexion.php';
$mensaje = "";

$id_editar = "";
$id_cliente_edit = "";
$fecha_edit = "";
$total_edit = "";
$modo_edicion = false;


if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    try {
        $sql_delete = "DELETE FROM pedidos WHERE id_pedido = :id";
        $stmt_del = $pdo->prepare($sql_delete);
        $stmt_del->execute([':id' => $id_eliminar]);
        header("Location: pedidos.php"); 
        exit();
    } catch (PDOException $e) {
        $mensaje = "<p style='color: red;'>Error al eliminar.</p>";
    }
}

if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $modo_edicion = true;
    
    $sql_edit = "SELECT * FROM pedidos WHERE id_pedido = :id";
    $stmt_edit = $pdo->prepare($sql_edit);
    $stmt_edit->execute([':id' => $id_editar]);
    $pedido = $stmt_edit->fetch();
    
    if ($pedido) {
        $id_cliente_edit = $pedido['id_cliente'];
        $fecha_edit = $pedido['fecha'];
        $total_edit = $pedido['total'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];

    if ($_POST['accion'] == 'Registrar') {
        try {
            $sql = "INSERT INTO pedidos (id_cliente, fecha, total) VALUES (:id_cliente, :fecha, :total)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_cliente' => $id_cliente, ':fecha' => $fecha, ':total' => $total]);
            $mensaje = "<p style='color: green;'>Pedido registrado correctamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al registrar: " . $e->getMessage() . "</p>";
        }
    } elseif ($_POST['accion'] == 'Actualizar') {
        $id_actualizar = $_POST['id_pedido'];
        try {
            $sql_update = "UPDATE pedidos SET id_cliente = :id_cliente, fecha = :fecha, total = :total WHERE id_pedido = :id";
            $stmt_upd = $pdo->prepare($sql_update);
            $stmt_upd->execute([':id_cliente' => $id_cliente, ':fecha' => $fecha, ':total' => $total, ':id' => $id_actualizar]);
            $mensaje = "<p style='color: green;'>Pedido actualizado correctamente.</p>";
            $modo_edicion = false;
            $id_cliente_edit = $fecha_edit = $total_edit = "";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al actualizar: " . $e->getMessage() . "</p>";
        }
    }
}

$sql_clientes = "SELECT id_cliente, nombre FROM clientes";
$lista_clientes = $pdo->query($sql_clientes)->fetchAll();

$sql_select = "SELECT p.id_pedido, p.fecha, p.total, c.nombre AS nombre_cliente 
               FROM pedidos p INNER JOIN clientes c ON p.id_cliente = c.id_cliente";
$pedidos = $pdo->query($sql_select)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>
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
        select, input { padding: 5px; margin-top: 5px; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-volver">← Volver al Inicio</a>
    <h1>Gestión de Pedidos</h1>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <h2 class="titulo-formulario">
            <?php echo $modo_edicion ? 'Modificar Pedido' : 'Registrar Nuevo Pedido'; ?>
        </h2>
        
        <form method="POST" action="pedidos.php">
            <?php if ($modo_edicion): ?>
                <input type="hidden" name="id_pedido" value="<?php echo $id_editar; ?>">
            <?php endif; ?>

            <label for="id_cliente">Cliente:</label><br>
            <select id="id_cliente" name="id_cliente" required>
                <option value="">-- Seleccione --</option>
                <?php foreach ($lista_clientes as $cli): ?>
                    <option value="<?php echo $cli['id_cliente']; ?>" 
                        <?php echo ($id_cliente_edit == $cli['id_cliente']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cli['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="fecha">Fecha:</label><br>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha_edit; ?>" required><br><br>

            <label for="total">Total:</label><br>
            <input type="number" step="0.01" min="0" id="total" name="total" value="<?php echo $total_edit; ?>" required><br><br>
            
            <?php if ($modo_edicion): ?>
                <input type="submit" name="accion" value="Actualizar" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                <a href="pedidos.php" style="margin-left: 10px; text-decoration: none; color: gray;">Cancelar</a>
            <?php else: ?>
                <input type="submit" name="accion" value="Registrar" style="padding: 10px 20px; cursor: pointer;">
            <?php endif; ?>
        </form>
    </div>

    <h2>Listado de Pedidos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo $pedido['id_pedido']; ?></td>
                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                <td><?php echo $pedido['fecha']; ?></td>
                <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                <td>
                    <a href="pedidos.php?editar=<?php echo $pedido['id_pedido']; ?>" class="btn-editar">Editar</a>
                    <a href="pedidos.php?eliminar=<?php echo $pedido['id_pedido']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
