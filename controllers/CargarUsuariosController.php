<?php
session_start();

// 1. Seguridad: Verificar que esté logueado y sea Administrador (Rol 1)
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { 
    header("Location: ../views/auth/login.php"); 
    exit(); 
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Usuario.php';

// 3. Instanciar y obtener la lista de usuarios
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->leerTodo();

// Al terminar, la variable $usuarios (que contiene la consulta) está lista para la vista
?>