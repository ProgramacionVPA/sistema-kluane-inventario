<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: ../auth/login.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }

require_once '../../models/Activo.php';
require_once '../../config/conexion.php'; 

$activoModel = new Activo();
$activo = $activoModel->obtenerPorId($_GET['id']);

// Conexión para cargar listas desplegables
$db = new Conexion();
$conn = $db->getConexion();

// 1. Cargar Empleados
$stmt = $conn->prepare("SELECT * FROM usuarios ORDER BY nombre_completo ASC");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Cargar Sedes (Proyectos)
$stmtSedes = $conn->prepare("SELECT * FROM sedes ORDER BY nombre ASC");
$stmtSedes->execute();
$sedes = $stmtSedes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-header-kluane {
            background-color: #0dcaf0; /* Info color */
            color: #fff;
            border-bottom: 0;
            border-radius: 10px 10px 0 0 !important;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
    
    <div class="container py-5 px-3">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="mb-3">
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>

                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                    <div class="card-header card-header-kluane py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-bounding-box me-2"></i> Asignar Custodio y Ubicación</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="alert alert-light border shadow-sm mb-4 d-flex align-items-center">
                            <i class="bi bi-laptop fs-1 text-info me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?php echo $activo['marca'] . " " . $activo['modelo']; ?></h6>
                                <small class="text-muted">Serie / Etiqueta: <span class="badge bg-secondary"><?php echo $activo['serie']; ?></span></small>
                            </div>
                        </div>

                        <form action="../../controllers/ActivoController.php?accion=asignar" method="POST">
                            <input type="hidden" name="id_activo" value="<?php echo $activo['id_activo']; ?>">

                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold small">NUEVO CUSTODIO (EMPLEADO)</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                                    <select name="id_usuario" class="form-select" required>
                                        <option value="">-- Seleccione Empleado --</option>
                                        <?php foreach($usuarios as $user): ?>
                                            <option value="<?php echo $user['id_usuario']; ?>">
                                                <?php echo $user['nombre_completo']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold small">UBICACIÓN / PROYECTO DE DESTINO</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <select name="id_sede" class="form-select" required>
                                        <option value="">-- Seleccione Proyecto --</option>
                                        <?php foreach($sedes as $sede): ?>
                                            <option value="<?php echo $sede['id_sede']; ?>" 
                                                <?php 
                                                // Pre-seleccionar la sede actual si existe
                                                if($activo['id_sede_actual'] == $sede['id_sede']) echo 'selected'; 
                                                ?>>
                                                <?php echo $sede['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-text ms-1 mt-1"><i class="bi bi-info-circle"></i> El equipo se moverá a este inventario.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold small">OBSERVACIONES</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <textarea name="observacion" class="form-control" rows="3" placeholder="Ej: Entrega de equipo nuevo, cambio de proyecto..."></textarea>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-info text-white fw-bold shadow-sm py-2">
                                    <i class="bi bi-check2-circle"></i> Confirmar Asignación
                                </button>
                                <a href="dashboard.php" class="btn btn-light border text-muted py-2">Cancelar</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>