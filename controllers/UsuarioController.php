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
            header("Location: ../views/admin/usuarios.php?msg=guardado");
        } elseif ($resultado === "DUPLICADO") {
            echo "<script>alert('Error: Ese correo ya existe.'); window.history.back();</script>";
        } else {
            echo "<script>alert('Error al guardar.'); window.history.back();</script>";
        }
    }

    // 2. EDITAR
    elseif ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $datos = [
            'id_usuario' => $_POST['id_usuario'],
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'rol' => $_POST['rol'],
            'sede' => $_POST['sede'],
            'area' => $_POST['area']
        ];

        if ($usuarioModel->actualizar($datos)) {
            header("Location: ../views/admin/usuarios.php?msg=actualizado");
        } else {
            echo "<script>alert('Error al actualizar.'); window.history.back();</script>";
        }
    }

    // 3. ELIMINAR
    elseif ($accion == 'eliminar' && isset($_GET['id'])) {
        if ($usuarioModel->eliminar($_GET['id'])) {
            header("Location: ../views/admin/usuarios.php?msg=eliminado");
        } else {
            echo "<script>alert('No se puede eliminar. El usuario tiene historial o activos.'); window.history.back();</script>";
        }
    }
}
?>