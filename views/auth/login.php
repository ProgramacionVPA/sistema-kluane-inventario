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
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary mb-0">KLUANE</h2>
            <p class="text-muted">Gestión de Infraestructura IT</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center shadow-sm py-2" style="font-size: 0.9rem;">
                <i class="bi bi-exclamation-triangle-fill"></i> Credenciales incorrectas.
            </div>
        <?php endif; ?>

        <form action="../../controllers/LoginController.php" method="POST">
            
            <div class="mb-3">
                <label for="email" class="form-label text-muted fw-semibold small">CORREO ELECTRÓNICO</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="admin@kluane.com" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-semibold small">CONTRASEÑA</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-kluane btn-lg shadow-sm">
                    INGRESAR <i class="bi bi-box-arrow-in-right ms-1"></i>
                </button>
            </div>

        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted"><i class="bi bi-headset"></i> ¿Problemas de acceso? Contacta a IT.</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>