<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ajusta la ruta si es necesario
require_once 'conexion.php'; // Archivo de configuración de la base de datos

session_start(); // Inicia la sesión

// Recibir datos del formulario
$username = $_POST['username']; // Nombre completo
$email = $_POST['email']; // Correo electrónico
$password = $_POST['password']; // Contraseña
$dni = $_POST['dni']; // DNI
$direccion = $_POST['direccion']; // Dirección
$telefono = $_POST['telefono']; // Teléfono
$obrasocial = $_POST['obrasocial']; // Obra social
$usuario = $dni; // Usar el DNI como nombre de usuario

// Hash de la contraseña
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Generar token de verificación único
$token_verificacion = bin2hex(random_bytes(32));

// Insertar datos en la tabla de usuarios
$sql_usuario = "INSERT INTO usuarios (nombre, correo, contraseña, rol, usuario) VALUES (?, ?, ?, 'paciente', ?)";
try {
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->execute([$username, $email, $password_hashed, $usuario]);

    // Obtener el ID del usuario recién insertado
    $usuario_id = $pdo->lastInsertId();

    // Insertar datos en la tabla de pacientes, incluyendo el token de verificación
    $sql_paciente = "INSERT INTO pacientes (usuario_id, edad, obra_social, correo_verificado, telefono, token_verificacion) VALUES (?, NULL, ?, FALSE, ?, ?)";
    $stmt_paciente = $pdo->prepare($sql_paciente);
    $stmt_paciente->execute([$usuario_id, $obrasocial, $telefono, $token_verificacion]);

    // Crear el enlace de verificación
    $enlace_verificacion = "http://localhost/proyectofinal/verificar_email.php?token=" . $token_verificacion . "&id=" . $usuario_id;

    // Enviar correo electrónico
    $asunto = "Verificación de correo electrónico - Clínica Salud y Bienestar";
    $mensaje = "Gracias por registrarte en Clínica Salud y Bienestar.\n\n";
    $mensaje .= "Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico:\n";
    $mensaje .= $enlace_verificacion . "\n\n";
    $mensaje .= "Si no te has registrado en nuestra clínica, ignora este correo electrónico.";

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host       = 'smtp-relay.brevo.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '899c4e001@smtp-brevo.com';
    $mail->Password   = 'k7YHJWyO4qKQF8mL';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('nexusdevssoft@gmail.com', 'Clínica Salud y Bienestar');
    $mail->addAddress($email, $username);
    $mail->addReplyTo('nexusdevssoft@gmail.com', 'Clínica Salud y Bienestar');

    $mail->isHTML(false);
    $mail->Subject = $asunto;
    $mail->Body    = $mensaje;

    try {
        $mail->send();
    } catch (Exception $e) {
        // Eliminar registros si falla el envío
        $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$usuario_id]);
        echo "Error al enviar el correo: " . $mail->ErrorInfo;
        exit();
    }
    // Mostrar un modal indicando que los datos fueron enviados correctamente
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <title>Registro Exitoso</title>
    </head>
    <body>
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Registro Exitoso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Los datos se han enviado correctamente. Por favor, revisa tu correo electrónico para verificar tu cuenta.
                    </div>
                    <div class="modal-footer">
                        <a href="index.html" class="btn btn-primary">Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var successModal = new bootstrap.Modal(document.getElementById("successModal"));
            successModal.show();
        </script>
    </body>
    </html>';
    exit();

} catch (PDOException $e) {
    echo "Error al registrar el usuario: " . $e->getMessage();
}
?>







