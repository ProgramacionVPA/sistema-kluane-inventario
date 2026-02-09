<?php
session_start();
require_once '../config/conexion.php';
require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $database = new Conexion();
    $db = $database->getConexion();

    $user = new Usuario($db);

    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    if($user->login()){
        
        // Guardar datos del usuario en la sesión
        $_SESSION['id_usuario'] = $user->id_usuario;
        $_SESSION['nombre_completo'] = $user->nombre_completo;
        $_SESSION['id_rol'] = $user->id_rol;
        $_SESSION['id_sede'] = $user->id_sede; // Importante para saber de qué campamento es

        // LÓGICA DE ROLES
        // Rol 1 = Administrador (Tú) -> Va al Dashboard General
        // Rol 2 = Logístico (Diego) -> Va directo a su Matriz de Campamento
        
        if ($user->id_rol == 1) {
            header("Location: ../views/admin/dashboard.php");
        } else {
            
            header("Location: ../views/admin/ver_matriz.php?sede=" . $user->id_sede);
        }
        exit(); 

    } else {
        
        echo "<script>
            alert('Correo o contraseña incorrectos.'); 
            window.location.href='../views/auth/login.php';
        </script>";
    }
}
?>