<?php
session_start();

// CORS (Solo para Desarrollo)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'conexion.php';

// Recibir DNI y contraseña del formulario
$dni = $_POST['dni'] ?? null;
$pass = $_POST['pass'] ?? null;

if (!$dni || !$pass) {
    echo json_encode(['error' => 'Faltan datos.']);
    exit();
}

try {
    // Buscar el usuario en la tabla usuarios
    $stmt = $pdo->prepare("SELECT id, usuario, contraseña, rol FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $dni, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Usuario encontrado, verificar contraseña
        if (password_verify($pass, $user['contraseña'])) {
            // Contraseña correcta: guardar datos en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['dni'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según el rol
            if ($user['rol'] === 'paciente') {
                header('Location: perfilpaciente.html');
            } elseif ($user['rol'] === 'medico') {
                header('Location: perfil_medico.html');
            } else {
                echo json_encode(['error' => 'Rol de usuario no reconocido.']);
                exit();
            }
            exit();
        } else {
            echo json_encode(['error' => 'Contraseña incorrecta.']);
        }
    } else {
        echo json_encode(['error' => 'Usuario no encontrado.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>





