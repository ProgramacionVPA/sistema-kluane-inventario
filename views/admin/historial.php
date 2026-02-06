<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: ../auth/login.php"); exit(); }
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
    <title>Historial - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary"><i class="bi bi-clock-history"></i> Historial de Movimientos</h2>
                <h5 class="text-muted">
                    <?php echo $equipo['marca'] . " " . $equipo['modelo']; ?> 
                    <span class="badge bg-dark"><?php echo $equipo['serie']; ?></span>
                </h5>
            </div>
            <a href="dashboard.php" class="btn btn-outline-secondary">Volver al Panel</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Tipo</th>
                                <th>Responsable / Custodio</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $historial->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo date("d/m/Y H:i", strtotime($row['fecha_asignacion'])); ?></td>
                                    
                                    <td>
                                        <span class="badge bg-primary"><?php echo $row['tipo_movimiento']; ?></span>
                                    </td>
                                    
                                    <td>
                                        <i class="bi bi-person-circle"></i> 
                                        <strong><?php echo $row['nombre_completo']; ?></strong><br>
                                        <small class="text-muted"><?php echo $row['email']; ?></small>
                                    </td>
                                    
                                    <td><?php echo $row['observacion']; ?></td>
                                </tr>
                            <?php } ?>
                            
                            <?php if ($historial->rowCount() == 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-4">
                                        <em>Este equipo aún no tiene movimientos registrados.</em>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>