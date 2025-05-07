<?php
$host = 'sql205.infinityfree.com'; // Cambia esto si tu base de datos está en otro servidor
$db = 'if0_38689787_Clinica2025'; // Nombre de tu base de datos
$user = 'if0_38689787'; // Tu usuario de la base de datos
$pass = 'Lavia932+'; // Tu contraseña de la base de datos

try {
    // Opciones de PDO para mejorar la seguridad y el manejo de errores
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Desactiva la emulación de prepared statements para mayor seguridad
    ];

    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, $options);

    // Comentamos o eliminamos esta línea para evitar problemas
    // echo "Conexión exitosa";

} catch (PDOException $e) {
    // En caso de error de conexión, devolver un mensaje amigable en formato JSON
    $error_message = 'Error de conexión a la base de datos: ' . $e->getMessage();
    error_log($error_message); // Guarda el error en el registro del servidor
    echo json_encode(['error' => $error_message]);
    exit();
}
