<?php
// Delegamos toda la lógica inicial al controlador
require_once '../../controllers/CargarNuevoUsuarioController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 px-3">
        <div class="card shadow border-0 col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card-header bg-success text-white"><h4>Registrar Usuario</h4></div>
            <div class="card-body">
                <form action="../../controllers/UsuarioController.php?accion=crear" method="POST">
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        <small class="text-muted">🔒 Se guardará segura.</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label>Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($r = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $r['id_rol']; ?>"><?php echo htmlspecialchars($r['nombre_rol']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label>Sede</label>
                            <select name="sede" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php while($s = $sedes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $s['id_sede']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control" placeholder="Ej: TI">
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-2">Guardar</button>
                    <a href="usuarios.php" class="btn btn-light border text-muted w-100">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>