<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Verificar que nos pasaron un ID
if (!isset($_GET['id'])) {
    die("Error: No se especificó ningún activo para generar el acta.");
}

// 3. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

// 4. Instanciar y buscar datos
$activoModel = new Activo();
$activo = $activoModel->obtenerDetallesPorId($_GET['id']);

if (!$activo) {
    die("Activo no encontrado.");
}

// A partir de aquí, la variable $activo ya está lista con todos los nombres correctos.
?>