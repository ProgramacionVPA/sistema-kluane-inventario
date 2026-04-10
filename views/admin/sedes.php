<?php require_once '../../controllers/CargarSedesController.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            <h3 class="text-primary fw-bold mb-0">Sedes y Proyectos</h3>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaSede">
                <i class="bi bi-plus-circle"></i> Nuevo Proyecto
            </button>
        </div>
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle-fill"></i> Acción realizada con éxito.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th class="text-start">Nombre del Proyecto / Sede</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $lista_sedes->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="<?php echo ($row['estado'] == 'Inactivo') ? 'table-secondary opacity-75' : ''; ?>">
                                <td class="text-muted">#<?php echo $row['id_sede']; ?></td>
                                <td class="text-start fw-bold"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td>
                                    <?php if($row['estado'] == 'Activo'): ?>
                                        <span class="badge bg-success rounded-pill px-3">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3">Cerrado</span>
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
                                               class="btn btn-sm btn-outline-warning" title="Cerrar Proyecto"
                                               onclick="return confirm('¿Seguro que desea CERRAR este proyecto? Ya no aparecerá en los menús de transferencias.');">
                                                <i class="bi bi-lock-fill"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="../../controllers/SedeController.php?accion=activar&id=<?php echo $row['id_sede']; ?>" 
                                               class="btn btn-sm btn-outline-success" title="Reabrir Proyecto"
                                               onclick="return confirm('¿Desea REABRIR este proyecto?');">
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
                <form action="../../controllers/SedeController.php?accion=crear" method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Registrar Nuevo Proyecto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-bold">Nombre de la Sede/Proyecto:</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Proyecto Fruta del Norte" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar Proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarSede" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-primary">
                <form action="../../controllers/SedeController.php?accion=editar" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Editar Nombre del Proyecto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_sede" id="edit_id_sede">
                        <label class="form-label fw-bold">Nombre de la Sede/Proyecto:</label>
                        <input type="text" name="nombre" id="edit_nombre_sede" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModalEditar(id, nombre) {
            document.getElementById('edit_id_sede').value = id;
            document.getElementById('edit_nombre_sede').value = nombre;
            var modal = new bootstrap.Modal(document.getElementById('modalEditarSede'));
            modal.show();
        }
    </script>
</body>
</html>