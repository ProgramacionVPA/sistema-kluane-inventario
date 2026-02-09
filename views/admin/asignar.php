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
    <title>Asignar Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">📋 Asignar Responsable y Ubicación</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary">
                            <strong>Equipo:</strong> <?php echo $activo['marca'] . " " . $activo['modelo']; ?><br>
                            <strong>Serie:</strong> <?php echo $activo['serie']; ?>
                        </div>

                        <form action="../../controllers/ActivoController.php?accion=asignar" method="POST">
                            <input type="hidden" name="id_activo" value="<?php echo $activo['id_activo']; ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nuevo Custodio (Empleado)</label>
                                <select name="id_usuario" class="form-select" required>
                                    <option value="">-- Seleccione Empleado --</option>
                                    <?php foreach($usuarios as $user): ?>
                                        <option value="<?php echo $user['id_usuario']; ?>">
                                            <?php echo $user['nombre_completo']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ubicación / Proyecto de Destino</label>
                                <select name="id_sede" class="form-select" required>
                                    <option value="">-- Seleccione Proyecto --</option>
                                    <?php foreach($sedes as $sede): ?>
                                        <option value="<?php echo $sede['id_sede']; ?>" 
                                            <?php // Pre-seleccionar la sede actual si existe
                                            if($activo['id_sede_actual'] == $sede['id_sede']) echo 'selected'; 
                                            ?>>
                                            <?php echo $sede['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">El equipo se moverá a este inventario.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observacion" class="form-control" rows="3" placeholder="Ej: Entrega de equipo nuevo, cambio de proyecto..."></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info text-white fw-bold">Confirmar Asignación</button>
                                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>