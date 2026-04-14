<?php
// La vista delega absolutamente toda la lógica al Controlador
require_once '../../controllers/CargarDashboardController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-box-seam me-2"></i> KLUANE INVENTARIO</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-3 mt-lg-0">
                    <span class="text-white me-3 mb-2 mb-lg-0">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
                        <small class="badge bg-light text-primary ms-1 user-badge"><?php echo $esColaborador ? 'Colaborador' : 'Admin/Tec'; ?></small>
                    </span>
                    <?php if($_SESSION['id_rol'] == 1): ?>
                        <a href="sedes.php" class="btn btn-outline-light btn-sm me-0 me-lg-2 mb-2 mb-lg-0" title="Gestionar Sedes/Proyectos">
                            <i class="bi bi-building"></i> Proyectos
                        </a>
                        <a href="usuarios.php" class="btn btn-outline-light btn-sm me-0 me-lg-2 mb-2 mb-lg-0" title="Gestionar Usuarios">
                            <i class="bi bi-people-fill"></i> Usuarios
                        </a>
                    <?php endif; ?>
                    <a href="../../controllers/Logout.php" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid container-lg mt-4 px-3">
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="mb-3 mb-md-0 text-center text-md-start">
                            <h4 class="mb-0 text-primary">
                                <i class="bi bi-<?php echo $esColaborador ? 'laptop' : 'speedometer2'; ?>"></i> 
                                <?php echo $esColaborador ? 'Mis Equipos Asignados' : 'Dashboard Ejecutivo y Matriz 07'; ?>
                            </h4>
                            <p class="text-muted mb-0 small">
                                <?php echo $esColaborador ? 'Consulta el estado de las herramientas a tu cargo' : 'Gestión centralizada de equipos y métricas en tiempo real'; ?>
                            </p>
                        </div>
                        
                        <?php if(!$esColaborador): ?>
                        <div class="d-flex action-buttons">
                            <a href="ver_matriz.php" class="btn btn-outline-primary shadow-sm me-md-2">
                                <i class="bi bi-table"></i> Ver Matriz 09
                            </a>
                            <a href="nuevo_activo.php" class="btn btn-success shadow-sm">
                                <i class="bi bi-plus-lg"></i> Nuevo Activo
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!$esColaborador): ?>
        <div class="row mb-4 g-3">
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-secondary text-center">
                        Distribución por Estado
                    </div>
                    <div class="card-body">
                        <canvas id="graficoEstados" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-secondary text-center">
                        Equipos por Proyecto (Total: <?php echo $totalActivos; ?> equipos)
                    </div>
                    <div class="card-body">
                        <canvas id="graficoSedes" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 mb-5">
            
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-list-ul"></i> Listado General de Activos</h6>
                <div class="input-group input-group-sm w-25 min-w-200px">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscador_dashboard" class="form-control" placeholder="Buscar por código, serie o responsable...">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">Código KLU</th>
                                <th>Equipo</th>
                                <th>Serie</th>
                                <th>Categoría</th>
                                <th>Sede</th>
                                <th>Custodio</th> 
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_dashboard">
                            <?php 
                            if ($resultado && $resultado->rowCount() > 0) {
                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { 
                            ?>
                                <tr class="fila-dash">
                                    <td class="ps-3 fw-bold text-primary"><?php echo htmlspecialchars($fila['codigo_interno']); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($fila['marca']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($fila['modelo']); ?></small>
                                    </td>
                                    <td><small class="text-secondary"><?php echo htmlspecialchars($fila['serie']); ?></small></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($fila['categoria']); ?></span></td>
                                    <td><?php echo htmlspecialchars($fila['sede']); ?></td>
                                    <td>
                                        <?php if($fila['responsable']): ?>
                                            <span class="badge bg-info text-dark shadow-sm">
                                                <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($fila['responsable']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border">Sin Asignar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $estadoClass = 'bg-success';
                                            if($fila['estado'] == 'Mantenimiento') $estadoClass = 'bg-warning text-dark';
                                            elseif($fila['estado'] == 'Dañado' || $fila['estado'] == 'Baja') $estadoClass = 'bg-danger';
                                            echo "<span class='badge $estadoClass shadow-sm'>" . htmlspecialchars($fila['estado']) . "</span>";
                                        ?>
                                    </td>
                        
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            
                                            <?php if(!$esColaborador): ?>
                                                <a href="asignar.php?id=<?php echo $fila['id_activo']; ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Asignar">
                                                   <i class="bi bi-person-check-fill"></i>
                                                </a>
                                                
                                                <a href="editar_activo.php?id=<?php echo $fila['id_activo']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar Equipo">
                                                   <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <?php if($fila['responsable'] || $fila['total_movimientos'] > 0): ?>
                                                    <button class="btn btn-sm btn-light text-muted border-start" disabled title="Bloqueado por Auditoría">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <a href="../../controllers/ActivoController.php?accion=eliminar&id=<?php echo $fila['id_activo']; ?>" 
                                                       class="btn btn-sm btn-outline-danger btn-eliminar-activo" 
                                                       title="Eliminar Activo">
                                                       <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <a href="historial.php?id=<?php echo $fila['id_activo']; ?>" 
                                               class="btn btn-sm btn-outline-secondary" title="Ver Historial">
                                               <i class="bi bi-clock-history"></i>
                                            </a>

                                            <a href="generar_acta.php?id=<?php echo $fila['id_activo']; ?>" 
                                                target="_blank" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Imprimir Acta">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                } 
                            } else {
                                echo "<tr><td colspan='8' class='text-center py-5'><i class='bi bi-inbox fs-1 text-muted'></i><br>No hay activos registrados.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <?php if(!$esColaborador): ?>
    <script>
        const DATOS_ESTADOS = <?php echo isset($jsonEstados) ? $jsonEstados : '[]'; ?>;
        const DATOS_SEDES = <?php echo isset($jsonSedes) ? $jsonSedes : '[]'; ?>;
    </script>
    <?php endif; ?>

    <script src="../../public/js/dashboard.js"></script>
    <script src="../../public/js/kluane_app.js"></script>
</body>
</html>