<?php
session_start();

// 1. Seguridad: Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) { 
    header("Location: ../auth/login.php"); 
    exit(); 
}

// Verificar que venga un ID de activo en la URL
if (!isset($_GET['id'])) { 
    header("Location: dashboard.php"); 
    exit(); 
}

// Requerir el modelo
require_once '../../models/Activo.php';

// 2. Instanciar el modelo
$activoModel = new Activo();

// 3. Obtener los datos (La lógica que antes ensuciaba la vista)
$activo = $activoModel->obtenerPorId($_GET['id']);

// Si el usuario pone un ID que no existe en la URL, lo regresamos al dashboard
if (!$activo) {
    header("Location: dashboard.php"); 
    exit();
}

// Obtener las listas desplegables usando las funciones nuevas que creaste
$usuarios = $activoModel->obtenerTodosUsuarios();
$sedes = $activoModel->obtenerTodasSedes();

// Llegado a este punto, las variables $activo, $usuarios y $sedes 
// ya están cargadas en memoria y listas para que la vista las dibuje.
?>