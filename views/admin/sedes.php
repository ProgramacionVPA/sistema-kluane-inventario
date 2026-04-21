<?php require_once '../../controllers/CargarSedesController.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-primary mb-4 shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-arrow-left-circle"></i> Volver al Panel
            </a>
            <span class="navbar-text text-white">Gestión Proyectos</span>
        </div>
    </nav>

    <div class="container col-md-10 col-lg-8">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary fw-bold mb-0"><i class="bi bi-buildings"></i> Sedes y Proyectos</h3>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaSede">
                <i class="bi bi-plus-circle"></i> Nuevo Proyecto
            </button>
        </div>

        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">ID</th>
                                <th class="text-start">Nombre del Proyecto / Sede</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $lista_sedes->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="<?php echo ($row['estado'] == 'Inactivo') ? 'table-secondary opacity-75' : ''; ?>">
                                <td class="text-muted">#<?php echo $row['id_sede']; ?></td>
                                <td class="text-start fw-bold text-dark"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td>
                                    <?php if($row['estado'] == 'Activo'): ?>
                                        <span class="badge bg-success rounded-pill px-3 shadow-sm">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 shadow-sm">Cerrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group shadow-sm">
                                        
                                        <button class="btn btn-sm btn-outline-primary" title="Editar Nombre"
                                                onclick="abrirModalEditar('<?php echo $row['id_sede']; ?>', '<?php echo htmlspecialchars($row['nombre'], ENT_QUOTES); ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <?php if($row['estado'] == 'Activo'): ?>
                                            <a href="../../controllers/SedeController.php?accion=desactivar&id=<?php echo $row['id_sede']; ?>" 
                                               class="btn btn-sm btn-outline-warning btn-estado-sede" 
                                               data-accion="cerrar" data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>"
                                               title="Cerrar Proyecto">
                                                <i class="bi bi-lock-fill"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="../../controllers/SedeController.php?accion=activar&id=<?php echo $row['id_sede']; ?>" 
                                               class="btn btn-sm btn-outline-success btn-estado-sede" 
                                               data-accion="reabrir" data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>"
                                               title="Reabrir Proyecto">
                                                <i class="bi bi-unlock-fill"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevaSede" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-success">
                <form id="formNuevaSede" action="../../controllers/SedeController.php?accion=crear" method="POST" novalidate>
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-building-add"></i> Registrar Nuevo Proyecto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-bold text-muted small">Nombre de la Sede/Proyecto:</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Proyecto Fruta del Norte" required>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">Guardar Proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarSede" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-primary">
                <form id="formEditarSede" action="../../controllers/SedeController.php?accion=editar" method="POST" novalidate>
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Nombre del Proyecto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_sede" id="edit_id_sede">
                        <label class="form-label fw-bold text-muted small">Nombre de la Sede/Proyecto:</label>
                        <input type="text" name="nombre" id="edit_nombre_sede" class="form-control" required>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script src="../../public/js/kluane_app.js"></script>
    <script src="../../public/js/sedes.js"></script>
</body>
</html>