<?php
session_start(); // Inicia la sesión

// CORS (Solo para Desarrollo) - ¡NO USAR EN PRODUCCIÓN!
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Establecer el tipo de contenido a JSON
header('Content-Type: application/json');

// Conectar a la base de datos
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    $error_message = 'No autenticado.';
    error_log("Error: " . $error_message);
    echo json_encode(['error' => $error_message]);
    exit();
}

// Obtener el DNI del usuario desde la sesión
$dni = $_SESSION['dni'] ?? null; // Usar operador null coalesce

// Verificar si el DNI está presente en la sesión
if (!$dni) {
    $error_message = 'DNI no encontrado en la sesión.';
    error_log("Error: " . $error_message);
    echo json_encode(['error' => $error_message]);
    exit();
}

// Sanitizar el DNI (opcional, pero recomendado)
$dni = filter_var($dni, FILTER_SANITIZE_STRING);

try {
    // Preparar la consulta SQL para obtener los datos del paciente
    $stmt = $pdo->prepare("SELECT dni, apellidoynombre, telefono, email, obrasocial, direccion FROM pacientes WHERE dni = :dni");
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR); // Especificar el tipo de dato
    $stmt->execute();

    // Obtener los datos del paciente
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paciente) {
        // El paciente existe, devolver los datos como JSON
        echo json_encode($paciente);
        error_log("Datos del paciente encontrados para el DNI: " . $dni);
    } else {
        // No se encontraron datos del paciente
        $error_message = 'No se encontraron datos del paciente para el DNI: ' . $dni;
        error_log("Error: " . $error_message);
        echo json_encode(['error' => $error_message]);
    }
} catch (PDOException $e) {
    // Error al acceder a la base de datos
    $error_message = 'Error al acceder a la base de datos: ' . $e->getMessage();
    error_log("Error PDOException: " . $error_message);
    echo json_encode(['error' => $error_message]);
}
?>



