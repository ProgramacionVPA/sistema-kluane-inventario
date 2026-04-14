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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
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
        
        <div class="card shadow border-0 rounded-3">
            
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-people-fill"></i> Listado de Personal</h6>
                <div class="input-group input-group-sm w-25 min-w-200px">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscador_usuarios" class="form-control" placeholder="Buscar nombre o email...">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">Nombre</th>
                                <th>Email</th>
                                <th class="text-center">Rol</th>
                                <th>Sede</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_usuarios">
                            <?php while($row = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="fila-usuario">
                                <td class="ps-3 fw-bold"><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-center">
                                    <?php 
                                        if($row['id_rol'] == 1) echo '<span class="badge bg-dark shadow-sm">Admin</span>';
                                        elseif($row['id_rol'] == 2) echo '<span class="badge bg-info text-dark shadow-sm">Técnico</span>';
                                        else echo '<span class="badge bg-secondary shadow-sm">Colaborador</span>';
                                    ?>
                                </td>
                                <td><span class="badge border border-secondary text-secondary"><?php echo htmlspecialchars($row['sede']); ?></span></td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if($row['id_usuario'] != $_SESSION['id_usuario']): ?>
                                            <a href="../../controllers/UsuarioController.php?accion=eliminar&id=<?php echo $row['id_usuario']; ?>" 
                                               class="btn btn-sm btn-outline-danger btn-eliminar-usuario" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light text-muted border-start" disabled title="No puedes eliminarte a ti mismo">
                                                <i class="bi bi-shield-lock"></i>
                                            </button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            
            // 1. BUSCADOR EN TIEMPO REAL
            $('#buscador_usuarios').on('keyup', function() {
                let valorBusqueda = $(this).val().toLowerCase();
                $("#tbody_usuarios tr.fila-usuario").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(valorBusqueda) > -1)
                });
            });

            // 2. CONFIRMACIÓN PRO PARA ELIMINAR
            $('.btn-eliminar-usuario').on('click', function(e) {
                e.preventDefault(); 
                const href = $(this).attr('href');

                Swal.fire({
                    title: '¿Eliminar a este usuario?',
                    text: "Si el usuario tiene equipos asignados, el sistema no te dejará borrarlo por seguridad.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                })
            });

            // 3. ATRAPAR MENSAJES DESDE LA URL (El cerebro del feedback)
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.has('msg')) {
                const msg = urlParams.get('msg');
                let titulo = '', texto = '', icono = 'success';
                
                if (msg === 'creado') {
                    titulo = '¡Usuario Creado!';
                    texto = 'El nuevo empleado ya puede acceder al sistema.';
                } else if (msg === 'ok') {
                    titulo = '¡Cambios Guardados!';
                    texto = 'El perfil del usuario fue actualizado.';
                } else if (msg === 'eliminado') {
                    titulo = 'Usuario Eliminado';
                    texto = 'El registro fue borrado de la base de datos.';
                }
                
                Swal.fire({
                    icon: icono,
                    title: titulo,
                    text: texto,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }

            if (urlParams.has('error')) {
                const error = urlParams.get('error');
                let titulo = 'Error Operativo', texto = 'No se pudo completar la acción.';
                
                if (error === 'duplicado') {
                    titulo = 'Correo Duplicado';
                    texto = 'Ese correo electrónico ya está registrado en otro usuario.';
                } else if (error === 'dependencias') {
                    titulo = 'No se puede eliminar';
                    texto = 'Este usuario tiene equipos asignados o historial en el sistema.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: titulo,
                    text: texto,
                    confirmButtonColor: '#dc3545'
                });
            }

            // Limpiar la URL para que no repita la alerta al recargar (F5)
            if (urlParams.has('msg') || urlParams.has('error')) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>