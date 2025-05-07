<?php
require 'conexion.php'; // Debe definir $pdo

$fecha = $_POST['fecha'] ?? null;
$turnosxdia = isset($_POST['turnosxdia']) ? (int) $_POST['turnosxdia'] : null;
$id_medico = isset($_POST['user_id']) ? (int) $_POST['user_id'] : null;

if (!$fecha || !$turnosxdia || !$id_medico) {
    echo "❌ Datos incompletos.";
    exit;
}

// Verificar si ya existe
$sql = "SELECT id FROM diashorarios WHERE medico_id = :medico_id AND fecha = :fecha";
$stmt = $pdo->prepare($sql);
$stmt->execute(['medico_id' => $id_medico, 'fecha' => $fecha]);
$existe = $stmt->fetch();

if ($existe) {
    echo "⚠️ Ya existe disponibilidad cargada para ese día.";
} else {
    $sql = "INSERT INTO diashorarios (medico_id, fecha, turnosxdia, turnosdisponibles, turnosasignados)
            VALUES (:medico_id, :fecha, :turnosxdia, :turnosdisponibles, 0)";
    $stmt = $pdo->prepare($sql);
    $ok = $stmt->execute([
        'medico_id' => $id_medico,
        'fecha' => $fecha,
        'turnosxdia' => $turnosxdia,
        'turnosdisponibles' => $turnosxdia
    ]);

    echo $ok ? "✅ Disponibilidad guardada." : "❌ Error al guardar.";
}
?>

