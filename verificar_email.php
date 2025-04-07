<?php
require_once 'conexion.php';

// Obtener token e ID del enlace
$token = $_GET['token'];
$id = $_GET['id'];

// Validar que el token y el ID no estén vacíos
if (empty($token) || empty($id)) {
    echo "Error: Token o ID inválido.";
    exit;
}

// Preparar la consulta para buscar el usuario con el token y el ID
$sql = "SELECT * FROM pacientes WHERE token_verificacion = ? AND id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token, $id]);
$usuario = $stmt->fetch();

// Si se encuentra el usuario
if ($usuario) {
    // Actualizar el campo email_verificado a TRUE
    $sql = "UPDATE pacientes SET email_verificado = TRUE, token_verificacion = NULL WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Redirigir al usuario a la página de inicio de sesión o a una página de éxito
    header("Location: loginpaciente.html?verificacion=exitosa"); // Puedes pasar un parámetro para mostrar un mensaje de éxito
    exit;
} else {
    // Mostrar un mensaje de error si no se encuentra el usuario
    echo "Error: El enlace de verificación es inválido o ya ha sido utilizado.";
}
?>


