<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Verificar que nos pasaron la sede
if (!isset($_GET['sede'])) {
    die("Error: Seleccione una sede.");
}

// 3. Requerir el modelo
require_once __DIR__ . '/../models/Matriz.php';

$id_sede = $_GET['sede'];
$matrizModel = new Matriz();
$datos = $matrizModel->obtenerDatosMatriz($id_sede);

// 4. Variables iniciales para el reporte
$nombre_sede = "Proyecto Desconocido"; 
$filas = [];
$total_operativos = 0;
$total_danados = 0;
$conteo_laptops = 0;
$conteo_radios = 0;

// 5. Lógica de negocio: Contar y preparar datos
while ($row = $datos->fetch(PDO::FETCH_ASSOC)) {
    $filas[] = $row;
    
    if (!empty($row['ubicacion'])) {
        $nombre_sede = $row['ubicacion']; // Capturamos el nombre real del campamento
    }
    
    // Contadores para el encabezado (KPIs)
    if($row['estado'] == 'Operativo') $total_operativos++;
    if($row['estado'] == 'Dañado') $total_danados++;
    if(strpos(strtoupper($row['equipo']), 'LAPTOP') !== false) $conteo_laptops++;
    if(strpos(strtoupper($row['equipo']), 'RADIO') !== false) $conteo_radios++;
}

// Al terminar este script, $filas y los contadores están listos para ser impresos.
?>