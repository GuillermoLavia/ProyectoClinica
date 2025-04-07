<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ajusta la ruta si es necesario

// Incluye el archivo de configuración de la base de datos
require_once 'conexion.php';

// Inicia la sesión (si es necesario para tu aplicación)
session_start();

// Recibir datos del formulario
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$dni = $_POST['dni'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$obrasocial = $_POST['obrasocial'];

// Hash de la contraseña (¡IMPRESCINDIBLE!)
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Generar token de verificación único
$token_verificacion = bin2hex(random_bytes(32));

// Insertar datos en la base de datos (USANDO CONSULTA PREPARADA)
$sql = "INSERT INTO pacientes (apellidoynombre, dni, direccion, telefono, obrasocial, email, password, token_verificacion, email_verificado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, FALSE)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $dni, $direccion, $telefono, $obrasocial, $email, $password_hashed, $token_verificacion]);

    // Obtener el ID del paciente recién insertado
    $paciente_id = $pdo->lastInsertId();

    // Crear el enlace de verificación
    $enlace_verificacion = "http://localhost/proyectofinal/verificar_email.php?token=" . $token_verificacion . "&id=" . $paciente_id;

    // Enviar correo electrónico (USANDO PHPMailer)
    $asunto = "Verificación de correo electrónico - Clínica Salud y Bienestar";
    $mensaje = "Gracias por registrarte en Clínica Salud y Bienestar.\n\n";
    $mensaje .= "Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico:\n";
    $mensaje .= $enlace_verificacion . "\n\n";
    $mensaje .= "Si no te has registrado en nuestra clínica, ignora este correo electrónico.";

    try {
        $mail = new PHPMailer(true);

        //Configuración del servidor SMTP
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Quita esto en producción (muestra información de depuración)
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';  // Reemplaza con tu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = '899c4e001@smtp-brevo.com';       // Reemplaza con tu usuario SMTP
        $mail->Password   = 'k7YHJWyO4qKQF8mL';   // Reemplaza con tu contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o PHPMailer::ENCRYPTION_SMTPS
        $mail->Port       = 587;                     // Reemplaza con el puerto SMTP (587 para STARTTLS, 465 para SMTPS)

        //Configuración del correo electrónico
        $mail->setFrom('nexusdevssoft@gmail.com', 'Clínica Salud y Bienestar');
        $mail->addAddress($email, $username);     // Destinatario
        $mail->addReplyTo('nexusdevssoft@gmail.com', 'Clínica Salud y Bienestar');

        //Contenido del correo electrónico
        $mail->isHTML(false);  // No uses HTML para mayor seguridad (a menos que sea necesario)
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        $mail->send();

        // Redirigir a la página de éxito
        header("Location: exito_verificado.html");
        exit();

    } catch (Exception $e) {
        echo "¡Registro exitoso! Sin embargo, no se pudo enviar el correo electrónico de verificación. Por favor, contacta con el soporte. Error: " . $mail->ErrorInfo;
    }

} catch (PDOException $e) {
    echo "Error al registrar el usuario: " . $e->getMessage(); // Muestra el mensaje de error completo
}
?>






<!--<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Paciente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 400px;
        }

        .header {
            background-color: #306987;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .form-container {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group.column {
            flex-direction: row;
            justify-content: space-between;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"], input[type="email"], select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
            margin-bottom: 10px;
        }

        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, select:focus {
            border-color: #306987;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #306987;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #245d7e;
        }

        .toggle-link {
            text-align: center;
            margin-top: 15px;
        }

        .toggle-link a {
            color: #306987;
            text-decoration: none;
        }

        .error {
            border-color: red;
        }
    </style>
    <script>
        function validateForm(event) {
            const password = document.getElementById('password-reg');
            const passwordConfirm = document.getElementById('password-confirm');
            let isValid = true;

            // Limpia los estilos de error
            password.classList.remove('error');
            passwordConfirm.classList.remove('error');

            // Verifica si las contraseñas coinciden
            if (password.value !== passwordConfirm.value) {
                password.classList.add('error');
                passwordConfirm.classList.add('error');
                isValid = false;
                alert("Las contraseñas no coinciden. Por favor, corrígelas.");
            }

            // Si no es válido, evita el envío del formulario
            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
    
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Registro de Paciente</h2>
    </div>
    <div class="form-container">
        <form id="register-form" action="reg_user.php" method="post" onsubmit="validateForm(event)">
            <div class="form-group column">
                <div style="width: 48%;">
                    <label for="username-reg">Apellido y Nombre:</label>
                    <input type="text" id="username-reg" name="username" placeholder="Ej: Juan Pérez" required>
                </div>
                <div style="width: 48%;">
                    <label for="DNI">DNI:</label>
                    <input type="number" minlength="8" maxlength="8" id="DNI-reg" name="dni" placeholder="Ej: 12345678" required>
                </div>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ej: Av. Siempre Viva 742" required>
            </div>
            <div class="form-group column">
                <div style="width: 48%;">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ej: 011-1234-5678" required>
                </div>
                <div style="width: 48%;">
                    <label for="obrasocial">Obra Social:</label>
                    <select id="obrasocial" name="obrasocial" required>
                        <option value="">Seleccionar</option>
                        <option value="IASEP">IASEP</option>
                        <option value="OSDE">OSDE</option>
                        <option value="PFA">PFA</option>
                        <option value="Otra">Otra</option>
                        <option value="No Tiene">No Tiene</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email-reg">Correo Electrónico:</label>
                <input type="email" id="email-reg" name="email" placeholder="Ej: ejemplo@correo.com" required>
            </div>
            <div class="form-group">
                <label for="password-reg">Contraseña:</label>
                <input type="password" id="password-reg" name="password" placeholder="Mínimo 8 caracteres" required>
            </div>
            <div class="form-group">
                <label for="password-confirm">Repetir Contraseña:</label>
                <input type="password" id="password-confirm" name="password-confirm" placeholder="Las Contraseñas deben ser iguales" required>
            </div>
            <button type="submit">Registrarse</button>
            <div class="toggle-link">
                <p>¿Ya tienes cuenta? <a href="index.html">Inicia sesión aquí</a></p>
            </div>
        </form>
       
    </div>
</div>

</body>
</html>-->
