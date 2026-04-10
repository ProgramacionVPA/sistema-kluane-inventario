<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Matriz.php';
$matrizModel = new Matriz();

// 3. Obtener Sedes (Lo guardamos en un Array para poder usarlo varias veces en la vista sin re-consultar)
$stmt_sedes = $matrizModel->obtenerSedes();
$lista_sedes = $stmt_sedes->fetchAll(PDO::FETCH_ASSOC);

// 4. Obtener Usuarios para el modal
$lista_usuarios = $matrizModel->obtenerUsuarios()->fetchAll(PDO::FETCH_ASSOC);

// 5. Lógica de Negocio: Determinar qué sede debe cargar
$id_sede_seleccionada = '';
if ($_SESSION['id_rol'] == 2) {
    // Si es logístico, a la fuerza ve su propia sede
    $id_sede_seleccionada = $_SESSION['id_sede'];
} elseif (isset($_GET['sede'])) {
    // Si es admin, ve la que haya seleccionado en el select
    $id_sede_seleccionada = $_GET['sede'];
}

// 6. Obtener los activos dependiendo del ROL
$filas_matriz = [];

if ($_SESSION['id_rol'] == 3) {
    // Si es Colaborador, SOLO ve sus propios equipos
    $stmt_matriz = $matrizModel->obtenerDatosPorColaborador($_SESSION['id_usuario']);
    $filas_matriz = $stmt_matriz->fetchAll(PDO::FETCH_ASSOC);
} elseif ($id_sede_seleccionada) {
    // Si es Admin o Logístico, ve todos los equipos de la Sede seleccionada
    $stmt_matriz = $matrizModel->obtenerDatosMatriz($id_sede_seleccionada);
    $filas_matriz = $stmt_matriz->fetchAll(PDO::FETCH_ASSOC);
}

// Al finalizar, $lista_sedes, $lista_usuarios y $filas_matriz están listos para la vista.
?>