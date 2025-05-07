<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No estás autenticado.']);
    exit;
}

include 'conexion.php';

try {
    $user_id = $_SESSION['user_id'];
    $rol = $_SESSION['rol'];

    $stmt = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['error' => 'Usuario no encontrado.']);
        exit;
    }

    // Devolver datos según el rol
    if ($rol === 'medico') {
        echo json_encode([
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol' => 'medico'
        ]);
    } elseif ($rol === 'paciente') {
        echo json_encode([
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol' => 'paciente'
        ]);
    } else {
        echo json_encode(['error' => 'Rol no reconocido.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>

