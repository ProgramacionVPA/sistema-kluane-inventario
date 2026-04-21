<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="login-body">

    <div class="login-card">
        <div class="mb-4">
            <img src="../../public/img/logo.png" alt="Logo de Kluane Drilling Ltd.: gráfico de taladro azul y naranja" class="login-logo">
            
            <h2 class="fw-bold text-primary mb-0">KLUANE</h2>
            <p class="text-muted fw-semibold mt-1">Gestión de Infraestructura IT</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center shadow-sm py-2 mb-4 border-0" style="font-size: 0.9rem; background-color: #ffe5e5;">
                <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Credenciales incorrectas.
            </div>
        <?php endif; ?>

        <form action="../../controllers/LoginController.php" method="POST">
            
            <div class="mb-3">
                <label for="email" class="form-label text-muted fw-bold small mb-1">CORREO ELECTRÓNICO</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" placeholder="admin@kluane.com" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-bold small mb-1">CONTRASEÑA</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-grid gap-2 mt-5">
                <button type="submit" class="btn btn-kluane btn-lg fw-bold rounded-3">
                    INGRESAR <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>
            </div>

        </form>
        
        <div class="text-center mt-4 pt-3 border-top">
            <small class="text-muted"><i class="bi bi-headset me-1"></i> ¿Problemas de acceso? Contacta a IT.</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>