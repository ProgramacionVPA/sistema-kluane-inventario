<?php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
// Nota: La ruta de redirección cambia un poco porque ahora estamos dentro de /controllers/
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

require_once __DIR__ . '/../models/Activo.php';

// 2. Inicializar variables que la Vista va a necesitar
$totalActivos = 0;
$resultado = null;
$jsonEstados = "[]";
$jsonSedes = "[]";
$esColaborador = ($_SESSION['id_rol'] == 3); // Verificamos si es Rol 3

// 3. Lógica de Negocio (El controlador decide qué pedirle al modelo)
$activoModel = new Activo();

if ($esColaborador) {
    // Si es colaborador, solo ve SUS equipos
    $resultado = $activoModel->leerPorUsuario($_SESSION['id_usuario']);
    $totalActivos = $resultado->rowCount();
} else {
    // Si es Admin (1) o Técnico (2), ve TODOS los equipos y preparamos datos para gráficos
    $resultado = $activoModel->leerTodo();
    $totalActivos = $activoModel->contarTotal();
    $jsonEstados = json_encode($activoModel->contarPorEstado());
    $jsonSedes = json_encode($activoModel->contarPorSede());
}

// Al terminar de ejecutarse este archivo, todas estas variables ya existen 
// y están listas para que la Vista simplemente las dibuje.
?>