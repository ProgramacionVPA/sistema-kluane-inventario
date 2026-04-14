<?php
// Delegamos toda la lógica inicial al controlador
require_once '../../controllers/CargarNuevoUsuarioController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 px-3">
        <div class="card shadow border-0 col-12 col-md-8 col-lg-6 mx-auto rounded-3">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Registrar Nuevo Usuario</h5>
            </div>
            <div class="card-body p-4">
                
                <form id="formNuevoUsuario" action="../../controllers/UsuarioController.php?accion=crear" method="POST" novalidate>
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small">Nombre Completo</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-muted small">Email Corporativo</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="usuario@kluane.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 p-3 bg-light border rounded">
                        <label class="form-label fw-bold text-dark small"><i class="bi bi-key-fill text-warning"></i> Contraseña de Acceso</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        <small class="text-muted d-block mt-1"><i class="bi bi-shield-check text-success"></i> La contraseña se encriptará antes de guardarse.</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small">Rol en el Sistema</label>
                            <select name="rol" id="rol" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($r = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $r['id_rol']; ?>"><?php echo htmlspecialchars($r['nombre_rol']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-muted small">Sede / Proyecto Base</label>
                            <select name="sede" id="sede" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Área / Departamento</label>
                        <input type="text" name="area" id="area" class="form-control" placeholder="Ej: Operaciones, TI, RRHH...">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="usuarios.php" class="btn btn-light border text-muted me-md-2 mb-2 mb-md-0 px-4">Cancelar</a>
                        <button type="submit" id="btnGuardarUsuario" class="btn btn-success fw-bold px-4">Guardar Usuario</button>
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
            
            $('#formNuevoUsuario').on('submit', function(e) {
                // 1. Obtenemos los valores obligatorios
                let nombre = $('#nombre').val().trim();
                let email = $('#email').val().trim();
                let password = $('#password').val().trim();
                let rol = $('#rol').val();
                let sede = $('#sede').val();

                // Validación de seguridad (Todos los campos requeridos)
                if (!nombre || !email || !password || !rol || !sede) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formulario Incompleto',
                        text: 'Por favor, llena todos los campos obligatorios antes de guardar.',
                        confirmButtonColor: '#198754'
                    });
                    return false;
                }

                // Validación de longitud de contraseña
                if (password.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Contraseña muy corta',
                        text: 'La contraseña debe tener al menos 6 caracteres por seguridad.',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                // 2. FEEDBACK VISUAL ANTES DE ENVIAR
                e.preventDefault(); 
                let form = this;
                let btn = $('#btnGuardarUsuario');

                btn.html('<span class="spinner-border spinner-border-sm"></span> Guardando...').prop('disabled', true);

                Swal.fire({
                    title: 'Creando Usuario',
                    text: 'Registrando al nuevo empleado en el sistema...',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 800, 
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