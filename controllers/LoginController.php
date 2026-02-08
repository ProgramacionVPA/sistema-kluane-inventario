<?php
session_start();
require_once '../config/conexion.php';
require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Instanciar base de datos y conexión
    $database = new Conexion();
    $db = $database->getConexion();

    // 2. Instanciar el objeto Usuario (AQUÍ ESTABA EL ERROR ANTES, NO SE USABA)
    $user = new Usuario($db);

    // 3. Pasar los datos del formulario al modelo
    $user->email = $_POST['email'];
    $user->password = $_POST['password']; // Aquí va el "123456"

    // 4. Llamar a la función login() del modelo
    // Esta función es la que contiene el password_verify() que arreglamos antes
    if($user->login()){
        
        // ¡ÉXITO! Guardamos las variables de sesión usando los datos del modelo
        $_SESSION['id_usuario'] = $user->id_usuario;
        $_SESSION['nombre_completo'] = $user->nombre_completo;
        $_SESSION['id_rol'] = $user->id_rol;
        $_SESSION['id_sede'] = $user->id_sede;

        // Redirigir al Dashboard
        header("Location: ../views/admin/dashboard.php");
    } else {
        // FALLO
        echo "<script>
            alert('Correo o contraseña incorrectos. Intente de nuevo.'); 
            window.location.href='../views/auth/login.php';
        </script>";
    }
}
?>