<?php
session_start();

// 1. Seguridad: Verificar si está logueado y es Administrador (Rol 1)
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Usuario.php';

// 3. Instanciar y obtener las listas para los combos (selects)
$uModel = new Usuario();
$roles = $uModel->obtenerRoles();
$sedes = $uModel->obtenerSedes();

// Al finalizar, $roles y $sedes están listos para que la vista los dibuje
?>