<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recibir datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 2. Conectar a la BD
    $conexion = new Conexion();
    $conn = $conexion->getConexion();

    try {
        // 3. Buscar si el usuario existe
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Verificar contraseña
        // NOTA: Por ahora comparamos texto plano para el Sprint 1. 
        // En el futuro usaremos password_verify() para mayor seguridad.
        if ($usuario && $password == $usuario['password']) {
            
            // ¡ÉXITO! Guardamos datos en sesión
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
            $_SESSION['usuario_rol'] = ($usuario['id_rol'] == 1) ? 'Administrador' : 'Técnico';

            // Redirigir al Dashboard
            header("Location: ../views/admin/dashboard.php");
            exit();

        } else {
            // FALLO: Regresar al login con error
            echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='../views/auth/login.php';</script>";
        }

    } catch (PDOException $e) {
        echo "Error en el sistema: " . $e->getMessage();
    }
}
?>