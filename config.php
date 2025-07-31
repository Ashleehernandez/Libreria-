<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'libreria';
$username = 'root';
$password = '';

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configurar el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar el modo de fetch por defecto
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para formatear fecha
function formatearFecha($fecha) {
    return date('d/m/Y', strtotime($fecha));
}

// Función para formatear precio
function formatearPrecio($precio) {
    return '$' . number_format($precio, 2);
}

// Función para limpiar datos de entrada
function limpiarDatos($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>