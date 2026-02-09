<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../models/Matriz.php';
$matrizModel = new Matriz();

$sedes = $matrizModel->obtenerSedes();

// Obtenemos TODOS los usuarios para ponerlos en el modal de edición
$lista_usuarios = $matrizModel->obtenerUsuarios()->fetchAll(PDO::FETCH_ASSOC);

$id_sede_seleccionada = '';
if ($_SESSION['id_rol'] == 2) {
    $id_sede_seleccionada = $_SESSION['id_sede'];
} elseif (isset($_GET['sede'])) {
    $id_sede_seleccionada = $_GET['sede'];
}

$datos_matriz = null;
if ($id_sede_seleccionada) {
    $datos_matriz = $matrizModel->obtenerDatosMatriz($id_sede_seleccionada);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz 09 - Gestión Campamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold">
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

    <div class="container-fluid px-4">
        
        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg']=='ok'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Cambios Guardados!</strong> La matriz se ha actualizado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif($_GET['msg']=='transfer'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-truck"></i> <strong>¡Transferencia Exitosa!</strong> El equipo ha sido enviado a la otra sede y ya no está en tu lista.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($_SESSION['id_rol'] == 1): ?>
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body bg-white rounded">
                    <form action="" method="GET" class="row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Seleccione Proyecto:</label>
                            <select name="sede" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <?php $sedes->execute(); while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>" <?php if($id_sede_seleccionada == $s['id_sede']) echo 'selected'; ?>>
                                        <?php echo $s['nombre']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Ver</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info shadow-sm mb-4">
                <strong>Hola, <?php echo $_SESSION['nombre_completo']; ?>.</strong> Gestiona los activos de tu campamento aquí.
            </div>
        <?php endif; ?>

        <?php if ($datos_matriz): ?>
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">INVENTARIO EN SITIO</h6>
                <a href="generar_matriz_pdf.php?sede=<?php echo $id_sede_seleccionada; ?>" target="_blank" class="btn btn-light btn-sm fw-bold text-success">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF OFICIAL
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center align-middle" style="font-size: 0.85rem;">
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
                        <tbody>
                            <?php 
                            $contador = 1;
                            if($datos_matriz->rowCount() > 0):
                                while($fila = $datos_matriz->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td class="text-start">
                                    <span class="fw-bold"><?php echo $fila['equipo']; ?></span><br>
                                    <small class="text-muted"><?php echo $fila['serie']; ?></small>
                                </td>
                                <td class="text-start">
                                    <i class="bi bi-person-fill"></i> <?php echo $fila['responsable'] ? $fila['responsable'] : '<span class="text-danger">Sin Asignar</span>'; ?><br>
                                    <span class="badge bg-secondary"><?php echo $fila['area']; ?></span>
                                </td>
                                <td>
                                    <?php if($fila['estado'] == 'Operativo'): ?>
                                        <span class="badge bg-success">OPERATIVO</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?php echo $fila['estado']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($fila['necesita_insumos'] == 'SI'): ?>
                                        <span class="badge bg-warning text-dark">SI</span>
                                    <?php else: ?>
                                        <span class="text-muted">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" 
                                            title="Editar Estado/Responsable"
                                            onclick="abrirModal(
                                                '<?php echo $fila['id_activo']; ?>', 
                                                '<?php echo $fila['id_responsable']; ?>', 
                                                '<?php echo $fila['estado']; ?>',
                                                '<?php echo $fila['necesita_insumos']; ?>'
                                            )">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-sm btn-warning" 
                                            title="Devolver / Transferir a otra Sede"
                                            onclick="abrirModalTransferir('<?php echo $fila['id_activo']; ?>', '<?php echo $fila['equipo']; ?>')">
                                        <i class="bi bi-truck"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; 
                            else: ?>
                                <tr><td colspan="6" class="p-4">No hay datos.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

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
                                        <?php echo $u['nombre_completo'] . " (" . $u['area'] . ")"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <select name="nuevo_estado" id="modal_estado" class="form-select">
                                    <option value="Operativo">Operativo</option>
                                    <option value="Dañado">Dañado</option>
                                    <option value="En Bodega">En Bodega</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
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
                                <?php 
                                    // Reiniciamos el cursor de sedes para volver a usarlo
                                    $sedes->execute(); 
                                    while($s = $sedes->fetch(PDO::FETCH_ASSOC)): 
                                ?>
                                    <option value="<?php echo $s['id_sede']; ?>">
                                        <?php echo $s['nombre']; ?>
                                    </option>
                                <?php endwhile; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModal(id, idResp, estado, insumos) {
            document.getElementById('modal_id_activo').value = id;
            document.getElementById('modal_responsable').value = idResp; 
            document.getElementById('modal_estado').value = estado;
            document.getElementById('modal_insumos').value = insumos || 'NO';
            
            var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
            myModal.show();
        }

        // Nueva función para Transferir
        function abrirModalTransferir(id, nombre) {
            document.getElementById('trans_id_activo').value = id;
            document.getElementById('trans_nombre_equipo').innerText = nombre;
            
            var myModal = new bootstrap.Modal(document.getElementById('modalTransferir'));
            myModal.show();
        }
    </script>
</body>
</html>