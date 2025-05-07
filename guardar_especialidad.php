<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['especialidad'])) {
    $especialidad = trim($_POST['especialidad']);

    try {
        // Verificar si ya existe
        $verificar = $pdo->prepare("SELECT COUNT(*) FROM especialidades WHERE especialidad = ?");
        $verificar->execute([$especialidad]);
        if ($verificar->fetchColumn() > 0) {
            echo 'existe';
            exit;
        }

        // Insertar nueva especialidad
        $stmt = $pdo->prepare("INSERT INTO especialidades (especialidad) VALUES (?)");
        if ($stmt->execute([$especialidad])) {
            echo 'ok';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error';
    }
} else {
    echo 'vacio';
}
