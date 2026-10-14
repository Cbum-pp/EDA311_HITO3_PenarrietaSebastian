<?php
require_once 'conexion.php';
$mensaje = "";

// Variables por defecto para el formulario
$id_editar = "";
$nombre_edit = "";
$email_edit = "";
$edad_edit = "";
$modo_edicion = false;

// --- LÓGICA PARA ELIMINAR ---
if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    try {
        $sql_delete = "DELETE FROM clientes WHERE id_cliente = :id";
        $stmt_del = $pdo->prepare($sql_delete);
        $stmt_del->execute([':id' => $id_eliminar]);
        header("Location: clientes.php"); 
        exit();
    } catch (PDOException $e) {
        $mensaje = "<p style='color: red;'>Error al eliminar: No puedes borrar un cliente con pedidos asociados.</p>";
    }
}

// --- LÓGICA PARA CARGAR DATOS (Preparar Edición) ---
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $modo_edicion = true;
    
    $sql_edit = "SELECT * FROM clientes WHERE id_cliente = :id";
    $stmt_edit = $pdo->prepare($sql_edit);
    $stmt_edit->execute([':id' => $id_editar]);
    $cliente = $stmt_edit->fetch();
    
    if ($cliente) {
        $nombre_edit = $cliente['nombre'];
        $email_edit = $cliente['email'];
        $edad_edit = $cliente['edad'];
    }
}

// --- LÓGICA PARA REGISTRAR O ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];

    if ($_POST['accion'] == 'Registrar') {
        try {
            $sql = "INSERT INTO clientes (nombre, email, edad) VALUES (:nombre, :email, :edad)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre, ':email' => $email, ':edad' => $edad]);
            $mensaje = "<p style='color: green;'>Cliente registrado correctamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al registrar (¿Email duplicado?): " . $e->getMessage() . "</p>";
        }
    } elseif ($_POST['accion'] == 'Actualizar') {
        $id_actualizar = $_POST['id_cliente'];
        try {
            $sql_update = "UPDATE clientes SET nombre = :nombre, email = :email, edad = :edad WHERE id_cliente = :id";
            $stmt_upd = $pdo->prepare($sql_update);
            $stmt_upd->execute([':nombre' => $nombre, ':email' => $email, ':edad' => $edad, ':id' => $id_actualizar]);
            $mensaje = "<p style='color: green;'>Cliente actualizado correctamente.</p>";
            $modo_edicion = false;
            $nombre_edit = $email_edit = $edad_edit = "";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al actualizar: " . $e->getMessage() . "</p>";
        }
    }
}

// --- CONSULTAR TODOS LOS CLIENTES ---
$sql_select = "SELECT * FROM clientes";
$stmt_select = $pdo->query($sql_select);
$clientes = $stmt_select->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
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
    <h1>Gestión de Clientes</h1>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <h2 class="titulo-formulario">
            <?php echo $modo_edicion ? 'Modificar Cliente Seleccionado' : 'Registrar Nuevo Cliente'; ?>
        </h2>
        
        <form method="POST" action="clientes.php">
            <?php if ($modo_edicion): ?>
                <input type="hidden" name="id_cliente" value="<?php echo $id_editar; ?>">
            <?php endif; ?>

            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre_edit); ?>" required><br><br>
            
            <label for="email">Correo Electrónico:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_edit); ?>" required><br><br>

            <label for="edad">Edad:</label><br>
            <input type="number" id="edad" name="edad" value="<?php echo $edad_edit; ?>" required min="18"><br><br>
            
            <?php if ($modo_edicion): ?>
                <input type="submit" name="accion" value="Actualizar" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                <a href="clientes.php" style="margin-left: 10px; text-decoration: none; color: gray;">Cancelar</a>
            <?php else: ?>
                <input type="submit" name="accion" value="Registrar" style="padding: 10px 20px; cursor: pointer;">
            <?php endif; ?>
        </form>
    </div>

    <h2>Listado de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?php echo $cliente['id_cliente']; ?></td>
                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                <td><?php echo $cliente['edad']; ?></td>
                <td>
                    <a href="clientes.php?editar=<?php echo $cliente['id_cliente']; ?>" class="btn-editar">Editar</a>
                    <a href="clientes.php?eliminar=<?php echo $cliente['id_cliente']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>