<?php
session_start();

// Seguridad: Solo admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../views/auth/login.php");
    exit();
}

require_once __DIR__ . '/../models/Sede.php';

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $sedeModel = new Sede();

    // 1. CREAR
    if ($accion == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = trim($_POST['nombre']);
        if ($sedeModel->crear($nombre)) {
            header("Location: ../views/admin/sedes.php?msg=guardado");
        } else {
            echo "<script>alert('Error al guardar.'); window.history.back();</script>";
        }
    }

    // 2. EDITAR
    elseif ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id_sede'];
        $nombre = trim($_POST['nombre']);
        if ($sedeModel->actualizar($id, $nombre)) {
            header("Location: ../views/admin/sedes.php?msg=actualizado");
        } else {
            echo "<script>alert('Error al actualizar.'); window.history.back();</script>";
        }
    }

    // 3. DESACTIVAR (Borrado Lógico)
    elseif ($accion == 'desactivar' && isset($_GET['id'])) {
        if ($sedeModel->cambiarEstado($_GET['id'], 'Inactivo')) {
            header("Location: ../views/admin/sedes.php?msg=desactivado");
        }
    }

    // 4. ACTIVAR (Restaurar)
    elseif ($accion == 'activar' && isset($_GET['id'])) {
        if ($sedeModel->cambiarEstado($_GET['id'], 'Activo')) {
            header("Location: ../views/admin/sedes.php?msg=activado");
        }
    }
}
?>