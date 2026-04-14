<?php
session_start();

// 1. Seguridad (Adaptada para AJAX)
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibir los datos del formulario
    $id_activo = $_POST['id_activo'];
    $id_sede_destino = $_POST['id_sede_destino'];
    $motivo = $_POST['motivo'];
    
    // Configurar el tipo de respuesta a JSON
    header('Content-Type: application/json; charset=utf-8');

    // Validación de campos vacíos
    if(empty($id_activo) || empty($id_sede_destino)){
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos para la transferencia.']);
        exit();
    }

    // Identificar de qué sede se está enviando (para otros procesos, aunque en AJAX no redirigimos)
    $sede_origen = isset($_SESSION['id_sede']) ? $_SESSION['id_sede'] : '';

    // 3. Ejecutar la transferencia en el modelo
    $activoModel = new Activo();
    $resultado = $activoModel->transferirActivo(
        $id_activo, 
        $id_sede_destino, 
        $_SESSION['id_usuario'], 
        $_SESSION['nombre_completo'], 
        $motivo
    );

    // 4. Respuesta AJAX
    if ($resultado) {
        // Éxito
        echo json_encode(['status' => 'success', 'message' => 'Transferencia exitosa']);
    } else {
        // Error
        echo json_encode(['status' => 'error', 'message' => 'Error crítico al intentar transferir el equipo.']);
    }
    exit();
}
?>