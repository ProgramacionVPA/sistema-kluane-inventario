<?php
session_start();
// 1. Seguridad
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Importar el Modelo
require_once '../../models/Activo.php';
$activoModel = new Activo();
$resultado = $activoModel->leerTodo();
$totalActivos = $activoModel->contarTotal();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">KLUANE INVENTARIO</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3"><i class="bi bi-person-circle"></i> <?php echo $_SESSION['usuario_nombre']; ?></span>
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
                            <h4 class="mb-0 text-primary">Matriz de Activos 07</h4>
                            <p class="text-muted mb-0">Gestión centralizada de equipos</p>
                        </div>
                        <a href="nuevo_activo.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo Activo</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase opacity-75">Total Activos</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $totalActivos; ?></h2>
                        <small>Equipos registrados</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase opacity-75">Operativos</h6>
                        <h2 class="display-4 fw-bold mb-0"><i class="bi bi-check-circle"></i></h2>
                        <small>Estado saludable</small>
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
                                <th>Custodio</th> <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Aquí empieza el bucle PHP para generar filas
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
                                        
                                    </td>
                                </tr>
                            <?php }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>