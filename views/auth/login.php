<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Kluane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background: white;
        }
        .btn-kluane {
            background-color: #0d6efd; 
            color: white;
            font-weight: bold;
        }
        .btn-kluane:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">KLUANE</h2>
            <p class="text-muted">Sistema de Inventario</p>
        </div>

        <form action="../../controllers/LoginController.php" method="POST">
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="admin@kluane.com" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-kluane btn-lg">INGRESAR</button>
            </div>

        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">¿Olvidaste tu contraseña? Contacta a IT.</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>