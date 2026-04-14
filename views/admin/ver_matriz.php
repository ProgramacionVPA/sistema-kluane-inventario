<?php
require_once '../../controllers/CargarMatrizController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz 09 - Gestión Campamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold text-truncate">
                <i class="bi bi-table"></i> EC-IT-F-09: GESTIÓN DE CAMPAMENTO
            </span>
            <div class="d-flex">
                <?php if($_SESSION['id_rol'] == 1): ?>
                    <a href="dashboard.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
                <?php else: ?>
                    <a href="../../controllers/Logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-2 px-md-4">
        
        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'ok'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Cambios Guardados!</strong> La matriz se ha actualizado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif($_GET['msg'] == 'transfer'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-truck"></i> <strong>¡Transferencia Exitosa!</strong> El equipo ha sido enviado a la otra sede y ya no está en tu lista.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($_SESSION['id_rol'] == 1): ?>
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body bg-white rounded d-flex align-items-center">
                    <label class="form-label fw-bold me-3 mb-0">Seleccione Proyecto:</label>
                    <select name="sede" id="select_sede" class="form-select w-auto" onchange="filtrarMatrizAjax()">
                        <option value="">-- Seleccione para cargar --</option>
                        <?php foreach($lista_sedes as $s): ?>
                            <option value="<?php echo $s['id_sede']; ?>" <?php if($id_sede_seleccionada == $s['id_sede']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($s['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="#" id="btn_pdf" target="_blank" class="btn btn-outline-danger ms-auto d-none fw-bold">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF OFICIAL
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info shadow-sm mb-4">
                <strong>Hola, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>.</strong> Gestiona los activos de tu campamento aquí.
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam"></i> INVENTARIO EN SITIO</h6>
                <div class="input-group input-group-sm w-25">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscador_matriz" class="form-control" placeholder="Buscar serie, equipo, área...">
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center align-middle" style="font-size: 0.85rem; min-width: 700px;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Equipo</th>
                                <th>Responsable / Área</th>
                                <th>Estado</th>
                                <th>Insumos?</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_matriz">
                            <tr>
                                <td colspan="6" class="p-5 text-muted text-center">
                                    <i class="bi bi-arrow-up-circle fs-1"></i><br>
                                    Seleccione un proyecto arriba para ver el inventario.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if($_SESSION['id_rol'] != 3): ?>
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../../controllers/ActualizarEstado.php" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Editar Datos de Campamento</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_activo" id="modal_id_activo">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Responsable Actual:</label>
                            <select name="id_responsable" id="modal_responsable" class="form-select">
                                <option value="">-- Sin Asignar / En Bodega --</option>
                                <?php foreach($lista_usuarios as $u): ?>
                                    <option value="<?php echo $u['id_usuario']; ?>">
                                        <?php echo htmlspecialchars($u['nombre_completo']) . " (" . htmlspecialchars($u['area']) . ")"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Estado:</label>
                                <select name="nuevo_estado" id="modal_estado" class="form-select">
                                    <option value="Operativo">Operativo</option>
                                    <option value="Dañado">Dañado</option>
                                    <option value="En Bodega">En Bodega</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">¿Necesita Insumos?</label>
                                <select name="necesita_insumos" id="modal_insumos" class="form-select">
                                    <option value="NO">No</option>
                                    <option value="SI">Sí (Solicitar)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observación:</label>
                            <textarea name="observacion" class="form-control" rows="2" placeholder="Detalle el cambio..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTransferir" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-warning">
                <form action="../../controllers/TransferirActivo.php" method="POST">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title"><i class="bi bi-truck"></i> Transferir / Devolver Equipo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_activo" id="trans_id_activo">
                        
                        <div class="alert alert-light border">
                            Vas a transferir el equipo: <strong id="trans_nombre_equipo" class="text-primary"></strong>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Destino (Sede):</label>
                            <select name="id_sede_destino" class="form-select" required>
                                <option value="">-- Seleccione Destino --</option>
                                <?php foreach($lista_sedes as $s): ?>
                                    <option value="<?php echo $s['id_sede']; ?>">
                                        <?php echo htmlspecialchars($s['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Si es devolución, selecciona "Matriz Quito".</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo del Envío:</label>
                            <textarea name="motivo" class="form-control" rows="2" placeholder="Ej: Equipo dañado, cambio de personal, devolución..." required></textarea>
                        </div>

                        <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Atención:</strong> El equipo desaparecerá de tu inventario inmediatamente.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Confirmar Envío</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const ID_ROL_USUARIO = <?php echo isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 'null'; ?>;
        const SEDE_POR_DEFECTO = '<?php echo isset($id_sede_seleccionada) ? $id_sede_seleccionada : ""; ?>';
    </script>

    <script src="../../public/js/matriz.js"></script>
</body>
</html>
</body>
</html>