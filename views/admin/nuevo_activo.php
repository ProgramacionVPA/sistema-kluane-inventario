<?php
// Delegamos la carga inicial al controlador
require_once '../../controllers/CargarNuevoActivoController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <div class="container mt-5 px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar Nuevo Activo</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(isset($_GET['error']) && $_GET['error'] == 'duplicado'): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> 
                                <strong>¡Error!</strong> El Código Interno ya está registrado. Por favor, verifique la etiqueta del equipo.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="../../controllers/ActivoController.php?accion=crear" method="POST">
                            
                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Código Interno (Etiqueta)</label>
                                    <input type="text" name="codigo" class="form-control" placeholder="Ej: KLU-LAP-055" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Número de Serie</label>
                                    <input type="text" name="serie" class="form-control" placeholder="SN..." required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Marca</label>
                                    <input type="text" name="marca" class="form-control" placeholder="Ej: Dell" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" name="modelo" class="form-control" placeholder="Ej: Latitude 5420" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-select" required>
                                    <option value="1">Computación</option>
                                    <option value="2">Periféricos</option>
                                    <option value="3">Herramientas IT</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sede / Ubicación</label>
                                <select name="sede" class="form-select" required>
                                    <option value="">Seleccione un proyecto...</option>
                                    <?php foreach ($sedes as $sede): ?>
                                        <option value="<?php echo $sede['id_sede']; ?>">
                                            <?php echo htmlspecialchars($sede['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Inicial</label>
                                <select name="estado" class="form-select">
                                    <option value="Operativo">Operativo</option>
                                    <option value="Mantenimiento">En Mantenimiento</option>
                                    <option value="Dañado">Dañado</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2 mb-2 mb-md-0">Cancelar</a>
                                <button type="submit" class="btn btn-success">Guardar Activo</button>
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