<?php
require_once 'conexion.php';

$token = $_GET['token'] ?? null;
$id = $_GET['id'] ?? null;

if (empty($token) || empty($id)) {
    echo "Error: Token o ID inválido.";
    exit;
}

$sql = "SELECT * FROM pacientes WHERE usuario_id = ? AND token_verificacion = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $token]);
$paciente = $stmt->fetch();

if ($paciente) {
    $sql = "UPDATE pacientes SET correo_verificado = TRUE, token_verificacion = NULL WHERE usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: loginpaciente.html?verificacion=exitosa");
    exit;
} else {
    echo "Error: El enlace de verificación es inválido o ya ha sido utilizado.";
}
?>
