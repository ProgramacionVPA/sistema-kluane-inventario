<?php
session_start();
require_once __DIR__ . '/../models/Activo.php';

// 1. SEGURIDAD: Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// Verificamos si hay una acción en la URL
if (isset($_GET['accion'])) {
    
    $accion = $_GET['accion'];
    $activoModel = new Activo();

    // CASO 1: CREAR UN NUEVO ACTIVO
    if ($accion == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $datos = [
            'codigo' => $_POST['codigo'],
            'serie' => $_POST['serie'],
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'categoria' => $_POST['categoria'],
            'sede' => $_POST['sede'],
            'estado' => $_POST['estado']
        ];

        // Guardamos la respuesta del modelo en una variable para evaluarla
        $resultado = $activoModel->crear($datos);

        if ($resultado === true) {
            header("Location: ../views/admin/dashboard.php?msg=guardado");
        } elseif ($resultado === "DUPLICADO") {
            // Si el modelo dice que es duplicado, lo mandamos de vuelta al formulario con el error
            header("Location: ../views/admin/nuevo_activo.php?error=duplicado");
        } else {
            echo "Hubo un error al guardar el activo.";
        }
    }

    // CASO 2: ELIMINAR UN ACTIVO
    elseif ($accion == 'eliminar' && isset($_GET['id'])) {
        
        $id = $_GET['id'];
        
        if ($activoModel->eliminar($id)) {
            header("Location: ../views/admin/dashboard.php?msg=eliminado");
        } else {
            echo "Error al eliminar el activo.";
        }
    }

    // CASO 3: EDITAR UN ACTIVO
    elseif ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $datos = [
            'id_activo' => $_POST['id_activo'],
            'codigo' => $_POST['codigo'],
            'serie' => $_POST['serie'],
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'estado' => $_POST['estado']
        ];

        if ($activoModel->actualizar($datos)) {
            header("Location: ../views/admin/dashboard.php?msg=actualizado");
        } else {
            echo "Error al actualizar el activo.";
        }
    }

    // CASO 4: ASIGNAR ACTIVO (MODIFICADO PARA INCLUIR SEDE)
    elseif ($accion == 'asignar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Recibimos los datos del formulario actualizado
        $id_activo = $_POST['id_activo'];
        $id_usuario = $_POST['id_usuario']; // El Custodio
        $id_sede = $_POST['id_sede'];       // La Nueva Ubicación (Proyecto)
        $observacion = $_POST['observacion'];

        // Validación básica
        if(empty($id_activo) || empty($id_usuario) || empty($id_sede)){
            echo "<script>alert('Error: Debe seleccionar un Custodio y una Sede.'); window.history.back();</script>";
            exit();
        }

        // Llamamos a la función asignar pasándole también la SEDE
        if ($activoModel->asignar($id_activo, $id_usuario, $id_sede, $observacion)) {
            header("Location: ../views/admin/dashboard.php?msg=asignado");
        } else {
            echo "Error al asignar.";
        }
    }
}
?>