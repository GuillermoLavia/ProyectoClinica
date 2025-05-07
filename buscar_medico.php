<?php
require_once 'conexion.php';

// Obtener especialidades
$stmt_especialidades = $pdo->query("SELECT id, especialidad FROM especialidades");
$especialidades = $stmt_especialidades->fetchAll(PDO::FETCH_ASSOC);

// Filtrado por especialidad (si se selecciona)
$filtro = isset($_GET['especialidad']) ? $_GET['especialidad'] : '';

// Obtener médicos con su especialidad
$sql = "SELECT usuarios.id, usuarios.nombre, especialidades.especialidad AS especialidad
        FROM usuarios
        INNER JOIN medicos ON usuarios.id = medicos.usuario_id
        INNER JOIN especialidades ON medicos.id_especialidad = especialidades.id
        WHERE usuarios.rol = 'medico'";

$params = [];
if ($filtro) {
    $sql .= " AND especialidades.id = ?";
    $params[] = $filtro;
}

$stmt_medicos = $pdo->prepare($sql);
$stmt_medicos->execute($params);
$medicos = $stmt_medicos->fetchAll(PDO::FETCH_ASSOC);
?>
