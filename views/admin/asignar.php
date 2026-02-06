<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: ../auth/login.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }

require_once '../../models/Activo.php';
require_once '../../config/conexion.php'; 

$activoModel = new Activo();
$activo = $activoModel->obtenerPorId($_GET['id']);

// Cargar empleados para la lista desplegable
$db = new Conexion();
$conn = $db->getConexion();
$stmt = $conn->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <h4 class="mb-0">📋 Asignar Responsable</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary">
                            <strong>Equipo:</strong> <?php echo $activo['marca'] . " " . $activo['modelo']; ?><br>
                            <strong>Serie:</strong> <?php echo $activo['serie']; ?>
                        </div>

                        <form action="../../controllers/ActivoController.php?accion=asignar" method="POST">
                            <input type="hidden" name="id_activo" value="<?php echo $activo['id_activo']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Nuevo Custodio (Empleado)</label>
                                <select name="id_usuario" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach($usuarios as $user): ?>
                                        <option value="<?php echo $user['id_usuario']; ?>">
                                            <?php echo $user['nombre_completo']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observacion" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info text-white">Confirmar Entrega</button>
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