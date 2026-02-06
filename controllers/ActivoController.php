<?php
session_start();
require_once __DIR__ . '/../models/Activo.php';

// Verificamos si hay una acción en la URL (ej: ?accion=crear)
if (isset($_GET['accion'])) {
    
    $accion = $_GET['accion'];
    $activoModel = new Activo();

    // CASO 1: CREAR UN NUEVO ACTIVO
    if ($accion == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Recolectamos los datos del formulario
        $datos = [
            'codigo' => $_POST['codigo'],
            'serie' => $_POST['serie'],
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'categoria' => $_POST['categoria'],
            'sede' => $_POST['sede'],
            'estado' => $_POST['estado']
        ];

        // Llamamos al modelo para guardar
        if ($activoModel->crear($datos)) {
            // Si guardó bien, volvemos al Dashboard
            header("Location: ../views/admin/dashboard.php?msg=guardado");
        } else {
            // Si falló, avisamos
            echo "Hubo un error al guardar el activo.";
        }
    }

    // CASO 2: ELIMINAR UN ACTIVO
    elseif ($accion == 'eliminar' && isset($_GET['id'])) {
        
        $id = $_GET['id'];
        
        // Llamamos al modelo para que borre
        if ($activoModel->eliminar($id)) {
            // Éxito: volvemos al dashboard con un mensaje en la URL
            header("Location: ../views/admin/dashboard.php?msg=eliminado");
        } else {
            echo "Error al eliminar el activo.";
        }
    }

    // CASO 3: EDITAR UN ACTIVO (Guardar cambios)
    elseif ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $datos = [
            'id_activo' => $_POST['id_activo'], // OJO: Este viene del campo oculto
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

    // CASO 4: ASIGNAR ACTIVO
    elseif ($accion == 'asignar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $id_activo = $_POST['id_activo'];
        $id_usuario = $_POST['id_usuario'];
        $observacion = $_POST['observacion'];

        if ($activoModel->asignar($id_activo, $id_usuario, $observacion)) {
            header("Location: ../views/admin/dashboard.php?msg=asignado");
        } else {
            echo "Error al asignar.";
        }
    }
}
?>