<?php
// Delegamos toda la lógica inicial al controlador
require_once '../../controllers/CargarEditarActivoController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-warning text-dark py-3">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Editar Activo: <?php echo htmlspecialchars($activo['codigo_interno']); ?></h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <form id="formEditarActivo" action="../../controllers/ActivoController.php?accion=editar" method="POST" novalidate>
                            
                            <input type="hidden" name="id_activo" value="<?php echo $activo['id_activo']; ?>">

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-muted small">Código Interno</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" value="<?php echo htmlspecialchars($activo['codigo_interno']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted small">Número de Serie</label>
                                    <input type="text" name="serie" id="serie" class="form-control" value="<?php echo htmlspecialchars($activo['serie']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-muted small">Marca</label>
                                    <input type="text" name="marca" id="marca" class="form-control" value="<?php echo htmlspecialchars($activo['marca']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted small">Modelo</label>
                                    <input type="text" name="modelo" id="modelo" class="form-control" value="<?php echo htmlspecialchars($activo['modelo']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small">Estado Actual</label>
                                <select name="estado" class="form-select">
                                    <option value="Operativo" <?php if($activo['estado'] == 'Operativo') echo 'selected'; ?>>Operativo</option>
                                    <option value="Mantenimiento" <?php if($activo['estado'] == 'Mantenimiento') echo 'selected'; ?>>En Mantenimiento</option>
                                    <option value="Dañado" <?php if($activo['estado'] == 'Dañado') echo 'selected'; ?>>Dañado</option>
                                    <option value="Baja" <?php if($activo['estado'] == 'Baja') echo 'selected'; ?>>De Baja</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="dashboard.php" class="btn btn-light border text-muted me-md-2 mb-2 mb-md-0 px-4">Cancelar</a>
                                <button type="submit" class="btn btn-warning fw-bold px-4">Guardar Cambios</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script src="../../public/js/kluane_app.js"></script>
</body>
</html>