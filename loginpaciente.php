<?php
session_start();

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['dni']) && isset($_POST['pass'])) {
        $dni = trim($_POST['dni']);
        $password = trim($_POST['pass']); // Sanitizar la contraseña

      

        // Validación del DNI
        if (strlen($dni) < 7 || !ctype_digit($dni)) {
            $_SESSION['error_message'] = "DNI inválido.";
            header("Location: loginpaciente.html");
            exit();
        }

        $sql = "SELECT * FROM pacientes WHERE dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "Contraseña hasheada de la base de datos: " . $user['password'] . "<br>"; // ¡CORREGIDO!

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) { // ¡CORREGIDO!
                // Contraseña correcta, verificar email_verificado
                if ($user['email_verificado'] == 1) {
                    // Email verificado, iniciar sesión
                    session_start(); 
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['dni'] = $user['dni'];
                    error_log("DNI guardado en la sesión: " . $_SESSION['dni']);

                    header("Location: perfilpaciente.html");
                    exit();
                } else {
                    // Email no verificado
                    $_SESSION['error_message'] = "Debe verificar su correo electrónico para acceder.";
                    header("Location: loginpaciente.html");
                    exit();
                }
            } else {
                // Contraseña incorrecta
                $_SESSION['error_message'] = "Credenciales incorrectas.";
                header("Location: loginpaciente.html");
                exit();
            }
        } else {
            // Usuario no encontrado
            $_SESSION['error_message'] = "Credenciales incorrectas.";
            header("Location: loginpaciente.html");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Por favor, completa todos los campos.";
        header("Location: loginpaciente.html");
        exit();
    }
} else {
    // Si alguien intenta acceder directamente a loginpaciente.php sin enviar el formulario
    $_SESSION['error_message'] = "Acceso no permitido."; // Mensaje más descriptivo
    header("Location: loginpaciente.html");
    exit();
}
?>

