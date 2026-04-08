<?php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

// 3. Instanciar el modelo y obtener datos dinámicos para el formulario
$activoModel = new Activo();
$sedes = $activoModel->obtenerTodasSedes();

// Nota: Las categorías las mantendremos estáticas por ahora en el HTML, 
// pero si en el futuro creas un modelo 'Categoria', lo cargarías aquí mismo.
?>