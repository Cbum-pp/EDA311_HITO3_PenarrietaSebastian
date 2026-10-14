<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Tienda Peñarrieta</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; text-align: center; background-color: #f4f4f9; }
        .menu { margin-top: 40px; }
        .btn-menu { 
            display: inline-block; 
            padding: 20px 40px; 
            margin: 15px; 
            background-color: #0078D7; 
            color: white; 
            text-decoration: none; 
            font-size: 18px; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
        }
        .btn-menu:hover { background-color: #005a9e; }
    </style>
</head>
<body>

    <h1>Sistema de Gestión - Tienda Peñarrieta</h1>
    <p>Seleccione el módulo al que desea ingresar:</p>

    <div class="menu">
        <a href="clientes.php" class="btn-menu">Gestión de Clientes</a>
        <a href="productos.php" class="btn-menu">Gestión de Productos</a>
        <a href="pedidos.php" class="btn-menu">Gestión de Pedidos</a>
    </div>

</body>
</html>