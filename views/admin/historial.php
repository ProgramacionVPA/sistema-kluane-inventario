<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: ../auth/login.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }

require_once '../../models/Activo.php';
$activoModel = new Activo();

// 1. Obtenemos datos del equipo (Encabezado)
$equipo = $activoModel->obtenerPorId($_GET['id']);

// 2. Obtenemos su historia (Lista)
$historial = $activoModel->obtenerHistorial($_GET['id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Ajuste fino para celulares: que los botones no se monten encima del texto */
        @media (max-width: 576px) {
            .header-historial {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }
        }
    </style>
</head>
<body class="bg-light">

    <div class="container mt-4 mt-md-5 px-3">
        
        <div class="d-flex justify-content-between align-items-center mb-4 header-historial">
            <div>
                <h2 class="text-primary fs-3 fs-md-2"><i class="bi bi-clock-history"></i> Historial de Movimientos</h2>
                <h5 class="text-muted fs-6 fs-md-5">
                    <?php echo $equipo['marca'] . " " . $equipo['modelo']; ?> 
                    <span class="badge bg-dark"><?php echo $equipo['serie']; ?></span>
                </h5>
            </div>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary w-100 d-md-none">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>

        <div class="card shadow border-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" style="min-width: 600px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3">Fecha y Hora</th>
                                <th>Tipo de Movimiento</th>
                                <th>Responsable / Custodio</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($historial->rowCount() > 0) {
                                while ($row = $historial->fetch(PDO::FETCH_ASSOC)) { 
                                    
                                    // 1. LÓGICA INTELIGENTE PARA DETECTAR EL TIPO SI ESTÁ VACÍO
                                    $tipo = trim($row['tipo_movimiento']);
                                    $obs_minusculas = strtolower($row['observacion']);

                                    if (empty($tipo)) {
                                        if (strpos($obs_minusculas, 'transferencia') !== false || strpos($obs_minusculas, 'devolución') !== false) {
                                            $tipo = 'Transferencia';
                                        } elseif (strpos($obs_minusculas, 'cambio') !== false || strpos($obs_minusculas, 'edición') !== false) {
                                            $tipo = 'Actualización';
                                        } else {
                                            $tipo = 'Registro';
                                        }
                                    }

                                    // 2. ASIGNACIÓN DE COLORES E ICONOS
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
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">
                                        <?php echo date("d/m/Y", strtotime($row['fecha_asignacion'])); ?><br>
                                        <small><i class="bi bi-clock"></i> <?php echo date("H:i", strtotime($row['fecha_asignacion'])); ?></small>
                                    </td>
                                    
                                    <td>
                                        <span class="badge <?php echo $badgeClass; ?> px-2 py-2 shadow-sm" style="font-size: 0.85rem;">
                                            <i class="bi <?php echo $icono; ?> me-1"></i> <?php echo strtoupper($tipo); ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2 border">
                                                <i class="bi bi-person-fill text-secondary"></i>
                                            </div>
                                            <div>
                                                <strong><?php echo $row['nombre_completo']; ?></strong><br>
                                                <small class="text-muted"><?php echo $row['email']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="text-dark"><?php echo $row['observacion']; ?></span>
                                    </td>
                                </tr>
                            <?php 
                                } // Fin del while
                            } else { 
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-5">
                                        <i class="bi bi-inbox fs-1 text-light"></i><br>
                                        <em>Este equipo aún no tiene movimientos registrados en su historial.</em>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>