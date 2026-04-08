<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibir los datos del formulario
    $id_activo = $_POST['id_activo'];
    $id_sede_destino = $_POST['id_sede_destino'];
    $motivo = $_POST['motivo'];
    
    // Validación de campos vacíos
    if(empty($id_activo) || empty($id_sede_destino)){
        echo "<script>alert('Error: Faltan datos para la transferencia.'); window.history.back();</script>";
        exit();
    }

    // Identificar de qué sede se está enviando para poder redirigir de vuelta a la misma pantalla
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

    // 4. Redirección
    if ($resultado) {
        // Éxito: Volver a la matriz con mensaje verde de transferencia
        header("Location: ../views/admin/ver_matriz.php?sede=" . $sede_origen . "&msg=transfer");
    } else {
        echo "<script>alert('Error crítico al intentar transferir el equipo.'); window.history.back();</script>";
    }
}
?>