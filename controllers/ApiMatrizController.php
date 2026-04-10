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

$datos = []; // Iniciamos un arreglo vacío por defecto

// 3. Lógica de seguridad separada por ROLES
if ($_SESSION['id_rol'] == 3) {
    
    // CASO A: Es Colaborador. 
    // Ignoramos la sede y traemos ÚNICAMENTE los equipos asignados a su ID.
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $matrizModel->obtenerDatosPorColaborador($id_usuario);
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    
    // CASO B: Es Administrador o Logístico. 
    // Ellos sí necesitan buscar por Sede.
    $id_sede = isset($_GET['sede']) ? $_GET['sede'] : '';

    // Si es logístico (Rol 2), forzamos su propia sede por seguridad
    if ($_SESSION['id_rol'] == 2) {
        $id_sede = $_SESSION['id_sede'];
    }

    // Si tenemos una sede válida, traemos toda la matriz de ese campamento
    if (!empty($id_sede)) {
        $stmt = $matrizModel->obtenerDatosMatriz($id_sede);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 4. Devolver la magia en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($datos);
?>