<?php
$host = 'localhost'; // Cambia esto si tu base de datos está en otro servidor
$db = 'clinica2025'; // Nombre de tu base de datos
$user = 'root'; // Tu usuario de la base de datos
$pass = ''; // Tu contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Comentamos o eliminamos esta línea para evitar problemas
    // echo "Conexión exitosa"; 
} catch (PDOException $e) {
    // En caso de error de conexión, devolver un mensaje amigable en formato JSON
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit();
}
