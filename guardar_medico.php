<?php
require_once 'conexion.php';

// Función para enviar el modal
function sendModal($message, $type = 'success') {
    $modal_id = 'myModal'; // ID del modal
    $modal_html = "
    <div class='modal' id='$modal_id'>
        <div class='modal-content'>
            <span class='close' onclick='closeModal(\"$modal_id\")'>&times;</span>
            <p>$message</p>
        </div>
    </div>
    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            window.location.href = 'medicos_registro.html'; // Redirigir
        }

        // Mostrar el modal automáticamente al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('$modal_id').style.display = 'block';
        });
    </script>
    <style>
        /* Estilos básicos para el modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    ";
    echo $modal_html;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $especialidad = $_POST['especialidad'];
    $matricula = $_POST['matricula'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña

    try {
        // Insertar los datos del médico
        $sql_medico = "INSERT INTO medicos (nombre, apellido, especialidad, matricula, password) VALUES (?, ?, ?, ?, ?)";
        $stmt_medico = $pdo->prepare($sql_medico);
        $stmt_medico->execute([$nombre, $apellido, $especialidad, $matricula, $password]);

        $medico_id = $pdo->lastInsertId(); // Obtener el ID del médico insertado

        // Procesar los horarios
        if (isset($_POST['horarios']) && is_array($_POST['horarios'])) {
            foreach ($_POST['horarios'] as $horario) {
                $dia_semana = $horario['dia_semana'];
                $turno_manana = isset($horario['turno_manana']) ? 1 : 0;
                $turno_tarde = isset($horario['turno_tarde']) ? 1 : 0;
                $hora_inicio = $horario['hora_inicio'];
                $hora_fin = $horario['hora_fin'];

                // Insertar los horarios del médico
                $sql_horario = "INSERT INTO horarios_medicos (medico_id, dia_semana, turno_manana, turno_tarde, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_horario = $pdo->prepare($sql_horario);
                $stmt_horario->execute([$medico_id, $dia_semana, $turno_manana, $turno_tarde, $hora_inicio, $hora_fin]);
            }
        }

        sendModal('Médico guardado con éxito.', 'success');

    } catch (PDOException $e) {
        // Enviar mensaje de error
        sendModal('Error al guardar el médico: ' . $e->getMessage(), 'error');
    }
} else {
    // Si no es una solicitud POST, devolver un error
    sendModal('Método no permitido.', 'error');
}
?>