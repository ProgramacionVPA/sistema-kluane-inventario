<?php
session_start();

// Seguridad: Solo admin puede ver esta pantalla
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { 
    header("Location: ../views/auth/login.php"); 
    exit(); 
}

require_once __DIR__ . '/../models/Sede.php';

$sedeModel = new Sede();
$lista_sedes = $sedeModel->leerTodo();
?>