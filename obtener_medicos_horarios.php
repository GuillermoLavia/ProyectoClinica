<?php
require_once 'conexion.php';

try {
    // Consulta para obtener los médicos con sus especialidades
    $sql_medicos = "SELECT * FROM medicos";
    $stmt_medicos = $pdo->prepare($sql_medicos);
    $stmt_medicos->execute();
    $medicos = $stmt_medicos->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener los horarios de los médicos
    $sql_horarios = "SELECT * FROM horarios_medicos";
    $stmt_horarios = $pdo->prepare($sql_horarios);
    $stmt_horarios->execute();
    $horarios = $stmt_horarios->fetchAll(PDO::FETCH_ASSOC);

    // Organizar los datos en un array asociativo
    $data = array(
        'medicos' => $medicos,
        'horarios' => $horarios
    );

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);

} catch (PDOException $e) {
    // Enviar mensaje de error
    echo json_encode(array('error' => 'Error al obtener los datos: ' . $e->getMessage()));
}
?>