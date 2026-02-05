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
}
?>