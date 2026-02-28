<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Recibir el ID que queremos editar
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once '../../models/Activo.php';
$activoModel = new Activo();
$activo = $activoModel->obtenerPorId($_GET['id']);

// Si no encuentra el activo, volver al dashboard
if (!$activo) {
    header("Location: dashboard.php?msg=no_encontrado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">✏️ Editar Activo: <?php echo htmlspecialchars($activo['codigo_interno']); ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/ActivoController.php?accion=editar" method="POST">
                            
                            <input type="hidden" name="id_activo" value="<?php echo $activo['id_activo']; ?>">

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Código Interno</label>
                                    <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($activo['codigo_interno']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Número de Serie</label>
                                    <input type="text" name="serie" class="form-control" value="<?php echo htmlspecialchars($activo['serie']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Marca</label>
                                    <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($activo['marca']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($activo['modelo']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Estado Actual</label>
                                <select name="estado" class="form-select">
                                    <option value="Operativo" <?php if($activo['estado'] == 'Operativo') echo 'selected'; ?>>Operativo</option>
                                    <option value="Mantenimiento" <?php if($activo['estado'] == 'Mantenimiento') echo 'selected'; ?>>En Mantenimiento</option>
                                    <option value="Dañado" <?php if($activo['estado'] == 'Dañado') echo 'selected'; ?>>Dañado</option>
                                    <option value="Baja" <?php if($activo['estado'] == 'Baja') echo 'selected'; ?>>De Baja</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2 mb-2 mb-md-0">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>