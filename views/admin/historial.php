<?php
// Delegamos el procesamiento al controlador
require_once '../../controllers/CargarHistorialController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="bg-light">

    <div class="container mt-4 mt-md-5 px-3">
        
        <div class="d-flex justify-content-between align-items-center mb-4 header-historial">
            <div>
                <h2 class="text-primary fs-3 fs-md-2"><i class="bi bi-clock-history"></i> Historial de Movimientos</h2>
                <h5 class="text-muted fs-6 fs-md-5">
                    <?php echo htmlspecialchars($equipo['marca'] . " " . $equipo['modelo']); ?> 
                    <span class="badge bg-dark"><?php echo htmlspecialchars($equipo['serie']); ?></span>
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
                            <?php if (count($listaHistorial) > 0): ?>
                                <?php foreach ($listaHistorial as $row): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">
                                            <?php echo date("d/m/Y", strtotime($row['fecha_asignacion'])); ?><br>
                                            <small><i class="bi bi-clock"></i> <?php echo date("H:i", strtotime($row['fecha_asignacion'])); ?></small>
                                        </td>
                                        
                                        <td>
                                            <span class="badge <?php echo $row['badgeClass']; ?> px-2 py-2 shadow-sm" style="font-size: 0.85rem;">
                                                <i class="bi <?php echo $row['icono']; ?> me-1"></i> <?php echo $row['tipo_procesado']; ?>
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2 border">
                                                    <i class="bi bi-person-fill text-secondary"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['nombre_completo']); ?></strong><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <span class="text-dark"><?php echo htmlspecialchars($row['observacion']); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-5">
                                        <i class="bi bi-inbox fs-1 text-light"></i><br>
                                        <em>Este equipo aún no tiene movimientos registrados en su historial.</em>
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