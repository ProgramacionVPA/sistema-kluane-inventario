<?php
session_start();
// Seguridad:Condicional, si no ha iniciado sesión, lo saca del sistema
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Sistema de Inventario KLUANE</span>
            <a href="../../controllers/Logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="alert alert-success">
            <h3>¡Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>!</h3>
            <p>Has ingresado correctamente como: <strong><?php echo $_SESSION['usuario_rol']; ?></strong></p>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <h5>Total Activos</h5>
                    <h2>0</h2> </div>
            </div>
        </div>
    </div>
</body>
</html>