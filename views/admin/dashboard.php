<?php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../models/Activo.php';

// Inicializar variables
$totalActivos = 0;
$resultado = null;
$jsonEstados = "[]";
$jsonSedes = "[]";

// Intentar cargar datos
if (class_exists('Activo')) {
    $activoModel = new Activo();
    $resultado = $activoModel->leerTodo();
    $totalActivos = $activoModel->contarTotal();
    
    // Obtenemos los datos para los gráficos y los convertimos a JSON
    $jsonEstados = json_encode($activoModel->contarPorEstado());
    $jsonSedes = json_encode($activoModel->contarPorSede());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">KLUANE INVENTARIO</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3"><i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre_completo']; ?></span>
                <a href="usuarios.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a>
                <a href="../../controllers/Logout.php" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-primary"><i class="bi bi-speedometer2"></i> Dashboard Ejecutivo y Matriz 07</h4>
                            <p class="text-muted mb-0">Gestión centralizada de equipos y métricas en tiempo real</p>
                        </div>
                        <a href="nuevo_activo.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo Activo</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-secondary">
                        Distribución por Estado
                    </div>
                    <div class="card-body">
                        <canvas id="graficoEstados" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-secondary">
                        Equipos por Proyecto / Sede (Total: <?php echo $totalActivos; ?> equipos)
                    </div>
                    <div class="card-body">
                        <canvas id="graficoSedes" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Código KLU</th>
                                <th>Equipo</th>
                                <th>Serie</th>
                                <th>Categoría</th>
                                <th>Sede</th>
                                <th>Custodio</th> 
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($resultado) {
                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { 
                            ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo $fila['codigo_interno']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo $fila['marca']; ?></div>
                                        <small class="text-muted"><?php echo $fila['modelo']; ?></small>
                                    </td>
                                    <td><?php echo $fila['serie']; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $fila['categoria']; ?></span></td>
                                    <td><?php echo $fila['sede']; ?></td>
                                    <td>
                                        <?php if($fila['responsable']): ?>
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-person"></i> <?php echo $fila['responsable']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border">Sin Asignar</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if($fila['estado'] == 'Operativo'): ?>
                                            <span class="badge bg-success">Operativo</span>
                                        <?php elseif($fila['estado'] == 'Mantenimiento'): ?>
                                            <span class="badge bg-warning text-dark">Mantenimiento</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?php echo $fila['estado']; ?></span>
                                        <?php endif; ?>
                                    </td>
                        
                                    <td>
                                        <a href="asignar.php?id=<?php echo $fila['id_activo']; ?>" 
                                           class="btn btn-sm btn-outline-info" title="Asignar">
                                           <i class="bi bi-person-check-fill"></i>
                                        </a>
                                        
                                        <a href="editar_activo.php?id=<?php echo $fila['id_activo']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                           <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <a href="../../controllers/ActivoController.php?accion=eliminar&id=<?php echo $fila['id_activo']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Estás seguro de eliminar este activo permanentemente?');">
                                           <i class="bi bi-trash"></i>
                                        </a>

                                        <a href="historial.php?id=<?php echo $fila['id_activo']; ?>" 
                                           class="btn btn-sm btn-outline-secondary" title="Ver Historial">
                                           <i class="bi bi-clock-history"></i>
                                        </a>

                                        <a href="generar_acta.php?id=<?php echo $fila['id_activo']; ?>" 
                                            target="_blank" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Imprimir Acta de Entrega">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                        </a>

                                        <a href="ver_matriz.php" class="btn btn-outline-primary mb-3">
                                            <i class="bi bi-table"></i> Ver Matriz 09 (Campamento)
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                } // Fin del while
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No hay activos registrados o no se pudo cargar el modelo.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Recibimos los datos de PHP y los pasamos a JavaScript
        const datosEstados = <?php echo $jsonEstados; ?>;
        const datosSedes = <?php echo $jsonSedes; ?>;

        // 1. Gráfico de Pastel (Estados)
        if(datosEstados.length > 0) {
            const labelsEstados = datosEstados.map(d => d.estado);
            const dataEstados = datosEstados.map(d => d.cantidad);
            
            // Asignar colores según el estado
            const coloresEstados = labelsEstados.map(estado => {
                if(estado === 'Operativo') return '#198754'; // Verde
                if(estado === 'Dañado') return '#dc3545'; // Rojo
                if(estado === 'Mantenimiento') return '#ffc107'; // Amarillo
                return '#6c757d'; // Gris
            });

            new Chart(document.getElementById('graficoEstados'), {
                type: 'doughnut',
                data: {
                    labels: labelsEstados,
                    datasets: [{
                        data: dataEstados,
                        backgroundColor: coloresEstados,
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        // 2. Gráfico de Barras (Sedes)
        if(datosSedes.length > 0) {
            const labelsSedes = datosSedes.map(d => d.sede);
            const dataSedes = datosSedes.map(d => d.cantidad);

            new Chart(document.getElementById('graficoSedes'), {
                type: 'bar',
                data: {
                    labels: labelsSedes,
                    datasets: [{
                        label: 'Equipos Registrados',
                        data: dataSedes,
                        backgroundColor: '#0d6efd',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    plugins: { legend: { display: false } }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>