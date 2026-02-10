<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { header("Location: ../auth/login.php"); exit(); }
require_once '../../models/Usuario.php';
$uModel = new Usuario();
$roles = $uModel->obtenerRoles();
$sedes = $uModel->obtenerSedes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0 col-md-8 mx-auto">
            <div class="card-header bg-success text-white"><h4>Registrar Usuario</h4></div>
            <div class="card-body">
                <form action="../../controllers/UsuarioController.php?accion=crear" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6"><label>Nombre Completo</label><input type="text" name="nombre" class="form-control" required></div>
                        <div class="col-md-6"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        <small class="text-muted">🔒 Se guardará segura.</small>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($r = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $r['id_rol']; ?>"><?php echo $r['nombre_rol']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Sede</label>
                            <select name="sede" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>"><?php echo $s['nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control" placeholder="Ej: TI">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Guardar</button>
                    <a href="usuarios.php" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>