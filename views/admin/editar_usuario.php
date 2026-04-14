<?php
// Delegamos toda la lógica inicial al controlador
require_once '../../controllers/CargarEditarUsuarioController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 px-3">
        <div class="card shadow border-0 col-12 col-md-8 col-lg-6 mx-auto rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i>Editar Usuario: <?php echo htmlspecialchars($user['nombre_completo']); ?></h5>
            </div>
            <div class="card-body p-4">
                
                <form id="formEditarUsuario" action="../../controllers/UsuarioController.php?accion=editar" method="POST" novalidate>
                    <input type="hidden" name="id_usuario" value="<?php echo $user['id_usuario']; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small">Nombre Completo</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-muted small">Email (Usuario)</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 p-3 bg-light border rounded">
                        <label class="form-label fw-bold text-danger small"><i class="bi bi-key"></i> Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" id="password" class="form-control border-danger-subtle" placeholder="Dejar vacío para NO cambiar la contraseña actual">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small">Rol en el Sistema</label>
                            <select name="rol" class="form-select">
                                <?php while($r = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $r['id_rol']; ?>" <?php if($r['id_rol'] == $user['id_rol']) echo 'selected'; ?>><?php echo htmlspecialchars($r['nombre_rol']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-muted small">Proyecto / Sede Base</label>
                            <select name="sede" class="form-select">
                                <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>" <?php if($s['id_sede'] == $user['id_sede']) echo 'selected'; ?>><?php echo htmlspecialchars($s['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Área / Departamento</label>
                        <input type="text" name="area" class="form-control" value="<?php echo htmlspecialchars($user['area']); ?>">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="usuarios.php" class="btn btn-light border text-muted me-md-2 mb-2 mb-md-0 px-4">Cancelar</a>
                        <button type="submit" id="btnActualizar" class="btn btn-primary fw-bold px-4">Actualizar Usuario</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            
            $('#formEditarUsuario').on('submit', function(e) {
                // 1. Obtenemos los valores
                let nombre = $('#nombre').val().trim();
                let email = $('#email').val().trim();
                let password = $('#password').val().trim(); // Atrapamos el password

                // Validación de seguridad (Campos obligatorios)
                if (!nombre || !email) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos requeridos',
                        text: 'El nombre y el correo electrónico no pueden estar vacíos.',
                        confirmButtonColor: '#0d6efd'
                    });
                    return false;
                }

                // NUEVO: Validación de contraseña (Solo si decidió escribir algo)
                if (password.length > 0 && password.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Contraseña muy corta',
                        text: 'Si deseas cambiar la contraseña, debe tener al menos 6 caracteres por seguridad.',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                // 2. FEEDBACK VISUAL ANTES DE ENVIAR
                e.preventDefault(); 
                let form = this;
                let btn = $('#btnActualizar');

                btn.html('<span class="spinner-border spinner-border-sm"></span> Guardando...').prop('disabled', true);

                Swal.fire({
                    title: 'Actualizando Perfil',
                    text: 'Guardando los datos del usuario...',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 800, // Breve pausa para que se vea la animación
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                }).then(() => {
                    // Enviamos el formulario al controlador PHP
                    form.submit();
                });
            });

        });
    </script>
</body>
</html>