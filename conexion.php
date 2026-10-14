<?php
$host = '10.0.40.85';
$dbname = 'tienda_penarrieta';
$username = 'u_penarrieta_sebastian';
$password = 'Sol#26H8wJ4'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>