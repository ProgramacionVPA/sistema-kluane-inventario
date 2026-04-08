<?php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Verificar que venga un ID en la URL
if (!isset($_GET['id'])) {
    header("Location: ../views/admin/dashboard.php");
    exit();
}

// 3. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

// 4. Instanciar el modelo y obtener la información
$activoModel = new Activo();
$activo = $activoModel->obtenerPorId($_GET['id']);

// 5. Si un usuario pone un ID inventado en la URL, lo regresamos al dashboard
if (!$activo) {
    header("Location: ../views/admin/dashboard.php?msg=no_encontrado");
    exit();
}

// Si todo sale bien, la variable $activo ya está en memoria lista para la vista.
?>