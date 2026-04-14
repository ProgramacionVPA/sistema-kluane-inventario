<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

// Seguridad: Solo admin puede entrar
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../views/auth/login.php");
    exit();
}

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $usuarioModel = new Usuario();

    // 1. CREAR
    if ($accion == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $datos = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'rol' => $_POST['rol'],
            'sede' => $_POST['sede'],
            'area' => $_POST['area']
        ];

        $resultado = $usuarioModel->crear($datos);

        if ($resultado === true) {
            header("Location: ../views/admin/usuarios.php?msg=creado");
        } elseif ($resultado === "DUPLICADO") {
            // Mandamos el error por URL en lugar del feo alert()
            header("Location: ../views/admin/usuarios.php?error=duplicado");
        } else {
            header("Location: ../views/admin/usuarios.php?error=bd");
        }
        exit();
    }

    // 2. EDITAR
    elseif ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $datos = [
            'id_usuario' => $_POST['id_usuario'],
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'password' => $_POST['password'], // Puede venir vacío, el modelo debe manejarlo
            'rol' => $_POST['rol'],
            'sede' => $_POST['sede'],
            'area' => $_POST['area']
        ];

        if ($usuarioModel->actualizar($datos)) {
            // Usamos msg=ok para que sea igual que la edición de activos
            header("Location: ../views/admin/usuarios.php?msg=ok");
        } else {
            header("Location: ../views/admin/usuarios.php?error=bd");
        }
        exit();
    }

    // 3. ELIMINAR
    elseif ($accion == 'eliminar' && isset($_GET['id'])) {
        if ($usuarioModel->eliminar($_GET['id'])) {
            header("Location: ../views/admin/usuarios.php?msg=eliminado");
        } else {
            // Error si el usuario tiene equipos asignados (llave foránea)
            header("Location: ../views/admin/usuarios.php?error=dependencias");
        }
        exit();
    }
}
?>