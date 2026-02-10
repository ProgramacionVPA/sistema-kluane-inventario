<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { header("Location: ../auth/login.php"); exit(); }
require_once '../../models/Usuario.php';
$uModel = new Usuario();
$user = $uModel->obtenerPorId($_GET['id']);
$roles = $uModel->obtenerRoles();
$sedes = $uModel->obtenerSedes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0 col-md-8 mx-auto">
            <div class="card-header bg-primary text-white"><h4>Editar: <?php echo $user['nombre_completo']; ?></h4></div>
            <div class="card-body">
                <form action="../../controllers/UsuarioController.php?accion=editar" method="POST">
                    <input type="hidden" name="id_usuario" value="<?php echo $user['id_usuario']; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo $user['nombre_completo']; ?>" required></div>
                        <div class="col-md-6"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-danger">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar vacío para NO cambiar">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Rol</label>
                            <select name="rol" class="form-select">
                                <?php while($r = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $r['id_rol']; ?>" <?php if($r['id_rol']==$user['id_rol']) echo 'selected'; ?>><?php echo $r['nombre_rol']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Sede</label>
                            <select name="sede" class="form-select">
                                <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>" <?php if($s['id_sede']==$user['id_sede']) echo 'selected'; ?>><?php echo $s['nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control" value="<?php echo $user['area']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                    <a href="usuarios.php" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>