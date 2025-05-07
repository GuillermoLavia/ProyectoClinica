<?php
require_once 'conexion.php';

if (!isset($pdo)) {
    die('Error: No se pudo establecer la conexión con la base de datos.');
}

// Verificar que todos los datos necesarios estén presentes
if (!isset($_POST['nombre'], $_POST['apellido'], $_POST['especialidad'], $_POST['password'], $_POST['usuario'])) {
    die('Error: Faltan datos en el formulario.');
}

$nombre = $_POST['nombre'] . ' ' . $_POST['apellido'];
$especialidad = $_POST['especialidad'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifrado seguro
$usuario = $_POST['usuario'];

$correo = $_POST['nombre'] . ' ' . $_POST['apellido'];; // Valor por defecto

try {
    // Insertar en la tabla usuarios (usando 'contraseña' en lugar de 'password')
    $sql_usuario = "INSERT INTO usuarios (usuario, nombre, contraseña, correo, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->execute([$usuario, $nombre, $password, $correo, 'medico']);

    $usuario_id = $pdo->lastInsertId(); // ID del nuevo usuario

    // Insertar en la tabla medicos (sólo usuario_id y id_especialidad)
    $sql_medico = "INSERT INTO medicos (usuario_id, id_especialidad) VALUES (?, ?)";
    $stmt_medico = $pdo->prepare($sql_medico);
    $stmt_medico->execute([$usuario_id, $especialidad]);

    // Mensaje de éxito
    echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
          <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
          <script>
              $(document).ready(function() {
                  alert('Médico guardado con éxito.');
                  window.location.href = 'index.html'; // Cambiar si quieres redirigir
              });
          </script>";

} catch (PDOException $e) {
    $error = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
          <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
          <script>
              $(document).ready(function() {
                  alert('Error al guardar el médico: {$error}');
                  window.history.back();
              });
          </script>";
}
?>
