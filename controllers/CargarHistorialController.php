<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) { 
    header("Location: ../views/auth/login.php"); 
    exit(); 
}
if (!isset($_GET['id'])) { 
    header("Location: ../views/admin/dashboard.php"); 
    exit(); 
}

require_once __DIR__ . '/../models/Activo.php';
$activoModel = new Activo();

// 2. Obtenemos datos del equipo
$equipo = $activoModel->obtenerPorId($_GET['id']);
if (!$equipo) {
    header("Location: ../views/admin/dashboard.php?msg=no_encontrado");
    exit();
}

// 3. Obtenemos su historia
$historialResult = $activoModel->obtenerHistorial($_GET['id']);

// 4. LÓGICA INTELIGENTE (Procesamos los datos antes de enviarlos a la vista)
$listaHistorial = [];

if ($historialResult->rowCount() > 0) {
    while ($row = $historialResult->fetch(PDO::FETCH_ASSOC)) { 
        
        $tipo = trim($row['tipo_movimiento']);
        $obs_minusculas = strtolower($row['observacion']);

        // Detectar tipo si está vacío
        if (empty($tipo)) {
            if (strpos($obs_minusculas, 'transferencia') !== false || strpos($obs_minusculas, 'devolución') !== false) {
                $tipo = 'Transferencia';
            } elseif (strpos($obs_minusculas, 'cambio') !== false || strpos($obs_minusculas, 'edición') !== false) {
                $tipo = 'Actualización';
            } else {
                $tipo = 'Registro';
            }
        }

        // Asignación de colores e íconos
        $badgeClass = 'bg-secondary';
        $icono = 'bi-info-circle';

        if ($tipo == 'Asignacion' || $tipo == 'Asignación') {
            $badgeClass = 'bg-primary';
            $icono = 'bi-person-check-fill';
        } elseif ($tipo == 'Transferencia') {
            $badgeClass = 'bg-warning text-dark';
            $icono = 'bi-truck';
        } elseif ($tipo == 'Actualización' || $tipo == 'Edición') {
            $badgeClass = 'bg-info text-dark';
            $icono = 'bi-pencil-square';
        } elseif ($tipo == 'Registro') {
            $badgeClass = 'bg-success';
            $icono = 'bi-plus-circle-fill';
        }

        // Guardamos los datos calculados dentro del mismo arreglo
        $row['tipo_procesado'] = strtoupper($tipo);
        $row['badgeClass'] = $badgeClass;
        $row['icono'] = $icono;

        // Añadimos la fila procesada a nuestra lista final
        $listaHistorial[] = $row;
    }
}

// Al finalizar, la variable $listaHistorial contiene todos los registros listos para mostrarse
?>