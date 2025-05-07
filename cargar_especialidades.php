<?php
require_once 'conexion.php';

try {
    $stmt = $pdo->prepare("SELECT id, especialidad FROM especialidades ORDER BY especialidad");
    $stmt->execute();
    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($especialidades);
} catch (PDOException $e) {
    echo json_encode([]);
}
