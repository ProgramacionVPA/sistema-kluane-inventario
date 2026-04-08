<?php
session_start();

// 1. Seguridad: Verificar si está logueado y es Administrador (Rol 1)
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Verificar que venga un ID en la URL
if (!isset($_GET['id'])) {
    header("Location: ../views/admin/usuarios.php");
    exit();
}

// 3. Requerir el modelo
require_once __DIR__ . '/../models/Usuario.php';

// 4. Instanciar el modelo y obtener la información
$uModel = new Usuario();
$user = $uModel->obtenerPorId($_GET['id']);

// 5. Si ponen un ID falso en la URL, lo regresamos a la lista de usuarios
if (!$user) {
    header("Location: ../views/admin/usuarios.php?msg=no_encontrado");
    exit();
}

// 6. Cargar listas para los <select>
$roles = $uModel->obtenerRoles();
$sedes = $uModel->obtenerSedes();

// Al finalizar, $user, $roles y $sedes están listas para la vista
?>