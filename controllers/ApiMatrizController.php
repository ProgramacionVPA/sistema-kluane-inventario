<?php
session_start();

// 1. Seguridad básica
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Matriz.php';
$matrizModel = new Matriz();

// 3. Identificar la sede pedida por AJAX
$id_sede = isset($_GET['sede']) ? $_GET['sede'] : '';

// Si es logístico (Rol 2), forzamos su propia sede por seguridad
if ($_SESSION['id_rol'] == 2) {
    $id_sede = $_SESSION['id_sede'];
}

// Si no hay sede seleccionada, devolvemos un arreglo vacío
if (empty($id_sede)) {
    echo json_encode([]);
    exit();
}

// 4. Buscar datos
$stmt = $matrizModel->obtenerDatosMatriz($id_sede);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Devolver la magia en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($datos);
?>