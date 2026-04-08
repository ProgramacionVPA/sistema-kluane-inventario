<?php
// Delegamos toda la lógica al controlador
require_once '../../controllers/CargarUsuariosController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Usuarios - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-primary mb-4 shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
            <span class="navbar-text text-white">Gestión de Personal</span>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary fw-bold mb-0">Usuarios del Sistema</h3>
            <a href="nuevo_usuario.php" class="btn btn-success shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
            </a>
        </div>
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                Acción realizada con éxito.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nombre</th>
                                <th>Email</th>
                                <th class="text-center">Rol</th>
                                <th>Sede</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-center">
                                    <?php 
                                        if($row['id_rol'] == 1) echo '<span class="badge bg-dark">Admin</span>';
                                        elseif($row['id_rol'] == 2) echo '<span class="badge bg-info text-dark">Técnico</span>';
                                        else echo '<span class="badge bg-secondary">Colaborador</span>';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['sede']); ?></td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if($row['id_usuario'] != $_SESSION['id_usuario']): ?>
                                            <a href="../../controllers/UsuarioController.php?accion=eliminar&id=<?php echo $row['id_usuario']; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('¿Eliminar usuario?');">
                                                <i class="bi bi-trash"></i>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>