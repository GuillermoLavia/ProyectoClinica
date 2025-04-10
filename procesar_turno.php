<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Validar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $paciente_id = $_POST['paciente_id'] ?? null;
    $medico_id = $_POST['medico_id'] ?? null;
    $motivo_consulta = $_POST['motivo'] ?? null;

    // Validar los campos obligatorios
    if (!$fecha || !$hora || !$paciente_id || !$medico_id) {
        die("Por favor, completa todos los campos obligatorios.");
    }

    try {
        $sql = "INSERT INTO turnos (fecha, hora, paciente_id, medico_id, motivo_consulta)
                VALUES (:fecha, :hora, :paciente_id, :medico_id, :motivo_consulta)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':medico_id', $medico_id);
        $stmt->bindParam(':motivo_consulta', $motivo_consulta);
        $stmt->execute();

        echo "Turno registrado correctamente.";
    } catch (PDOException $e) {
        echo "Error al registrar el turno: " . $e->getMessage();
    }
}
?>