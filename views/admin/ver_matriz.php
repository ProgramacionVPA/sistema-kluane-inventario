<?php
session_start();
// Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../models/Matriz.php';
$matrizModel = new Matriz();

// Cargar lista de proyectos (Sedes)
$sedes = $matrizModel->obtenerSedes();

// Si ya eligieron una sede, cargamos la tabla
$id_sede_seleccionada = isset($_GET['sede']) ? $_GET['sede'] : '';
$datos_matriz = null;

if ($id_sede_seleccionada) {
    $datos_matriz = $matrizModel->obtenerDatosMatriz($id_sede_seleccionada);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz 09 - Gestión Campamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold">
                <i class="bi bi-table"></i> EC-IT-F-09: MATRIZ DE GESTIÓN (CAMPAMENTO)
            </span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </nav>

    <div class="container-fluid px-4">
        
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body bg-white rounded">
                <form action="" method="GET" class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold text-primary">Seleccione el Proyecto / Pozo:</label>
                        <select name="sede" class="form-select border-primary" required>
                            <option value="">-- Seleccione Ubicación --</option>
                            <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $s['id_sede']; ?>" 
                                    <?php if($id_sede_seleccionada == $s['id_sede']) echo 'selected'; ?>>
                                    <?php echo $s['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-search"></i> GENERAR MATRIZ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($datos_matriz): ?>
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-uppercase fw-bold">Inventario Actualizado en Sitio</h6>
                <a href="generar_matriz_pdf.php?sede=<?php echo $id_sede_seleccionada; ?>" 
                    target="_blank" 
                    class="btn btn-danger btn-sm fw-bold">
                    <i class="bi bi-file-earmark-pdf-fill"></i> DESCARGAR PDF OFICIAL (EC-IT-F-09)
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>N°</th>
                                <th>Equipo</th>
                                <th>N° Serie</th>
                                <th>Responsable</th>
                                <th>Área</th>
                                <th>Fecha Asignación</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $contador = 1;
                            // Verificar si hay datos
                            if($datos_matriz->rowCount() > 0):
                                while($fila = $datos_matriz->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td class="fw-bold text-start"><?php echo $fila['equipo']; ?></td>
                                <td class="text-start"><?php echo $fila['serie']; ?></td>
                                <td class="text-start text-uppercase"><?php echo $fila['responsable'] ? $fila['responsable'] : '<span class="badge bg-warning text-dark">En Bodega</span>'; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $fila['area'] ? $fila['area'] : 'N/A'; ?></span></td>
                                <td><?php echo $fila['fecha_asignacion'] ? $fila['fecha_asignacion'] : '-'; ?></td>
                                <td><?php echo $fila['ubicacion']; ?></td>
                                <td>
                                    <?php if($fila['estado'] == 'Operativo'): ?>
                                        <span class="badge bg-success">OPERATIVO</span>
                                    <?php elseif($fila['estado'] == 'Dañado'): ?>
                                        <span class="badge bg-danger">DAÑADO</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?php echo $fila['estado']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; 
                            else: ?>
                                <tr><td colspan="8" class="p-5 text-muted fw-bold">No se encontraron equipos asignados a esta sede.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>