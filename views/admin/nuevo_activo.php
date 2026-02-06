<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Activo - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar Nuevo Activo</h4>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/ActivoController.php?accion=crear" method="POST">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Código Interno (Etiqueta)</label>
                                    <input type="text" name="codigo" class="form-control" placeholder="Ej: KLU-LAP-055" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Número de Serie</label>
                                    <input type="text" name="serie" class="form-control" placeholder="SN..." required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Marca</label>
                                    <input type="text" name="marca" class="form-control" placeholder="Ej: Dell" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" name="modelo" class="form-control" placeholder="Ej: Latitude 5420" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-select" required>
                                    <option value="1">Computación</option>
                                    <option value="2">Periféricos</option>
                                    <option value="3">Herramientas IT</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sede / Ubicación</label>
                                <select name="sede" class="form-select" required>
                                    <option value="1">Matriz Quito</option>
                                    <option value="2">Proyecto Macas</option>
                                    <option value="3">Proyecto Warintza</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Inicial</label>
                                <select name="estado" class="form-select">
                                    <option value="Operativo">Operativo</option>
                                    <option value="Mantenimiento">En Mantenimiento</option>
                                    <option value="Dañado">Dañado</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-success">Guardar Activo</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>