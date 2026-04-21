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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pc-display me-2"></i>Registrar Nuevo Equipo</h5>
                    </div>
                    <div class="card-body p-4">

                        <form id="formNuevoActivo" action="../../controllers/ActivoController.php?accion=crear" method="POST" novalidate>
                            
                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-muted small">Código Interno (Etiqueta KLU)</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Ej: KLU-LAP-055" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted small">Número de Serie (S/N)</label>
                                    <input type="text" name="serie" id="serie" class="form-control" placeholder="Ingresa el S/N..." required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-muted small">Marca</label>
                                    <input type="text" name="marca" id="marca" class="form-control" placeholder="Ej: Dell, HP..." required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted small">Modelo</label>
                                    <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ej: Latitude 5420" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">Categoría del Equipo</label>
                                <select name="categoria" id="categoria" class="form-select" required>
                                    <option value="">Seleccione una categoría...</option>
                                    <option value="1">Computación (Laptops, PCs)</option>
                                    <option value="2">Periféricos (Impresoras, Routers)</option>
                                    <option value="3">Herramientas IT (Antenas Starlink)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">Sede / Proyecto Inicial</label>
                                <select name="sede" id="sede" class="form-select" required>
                                    <option value="">Seleccione un proyecto...</option>
                                    <?php foreach ($sedes as $sede): ?>
                                        <option value="<?php echo $sede['id_sede']; ?>">
                                            <?php echo htmlspecialchars($sede['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text ms-1 mt-1"><i class="bi bi-info-circle"></i> El equipo se ingresará a la bodega de este proyecto.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small">Estado de Ingreso</label>
                                <select name="estado" class="form-select">
                                    <option value="Operativo" selected>Operativo (Listo para asignar)</option>
                                    <option value="Mantenimiento">En Mantenimiento (Requiere revisión)</option>
                                    <option value="Dañado">Dañado</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="dashboard.php" class="btn btn-light border text-muted me-md-2 mb-2 mb-md-0 px-4">Cancelar</a>
                                <button type="submit" id="btnGuardarActivo" class="btn btn-success fw-bold px-4">Registrar Activo</button>
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