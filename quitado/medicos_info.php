<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'ID de médico no especificado.']));
}

$id_medico = $_GET['id'];

// Asegurarse de que la variable $conexion esté definida correctamente
$conexion = $pdo;
$sql = "
    SELECT u.id, u.nombre, u.email
    FROM usuarios u
    WHERE u.rol = 'medico' AND u.id = :id
";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id', $id_medico, PDO::PARAM_INT);
$stmt->execute();

$medico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    die(json_encode(['error' => 'Médico no encontrado.']));
}

// Devuelve los datos como JSON
header('Content-Type: application/json');
echo json_encode($medico);
?>

