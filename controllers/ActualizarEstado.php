<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

// 2. Requerir el modelo
require_once __DIR__ . '/../models/Activo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibir los datos del formulario
    $id_activo = $_POST['id_activo'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $id_nuevo_responsable = $_POST['id_responsable']; 
    $necesita_insumos = $_POST['necesita_insumos']; 
    $observacion = $_POST['observacion'];

    // Preparamos el texto del historial
    $texto_obs = "Cambio realizado en sitio. " . trim($observacion);
    
    // Evitamos errores si la sede no está en sesión (para admins)
    $id_sede_actual = isset($_SESSION['id_sede']) ? $_SESSION['id_sede'] : null;

    // 3. Ejecutar la acción mediante el modelo
    $activoModel = new Activo();
    $resultado = $activoModel->actualizarEstadoEnCampo(
        $id_activo, 
        $nuevo_estado, 
        $id_nuevo_responsable, 
        $necesita_insumos, 
        $_SESSION['id_usuario'], 
        $id_sede_actual, 
        $texto_obs
    );

    // 4. Redirección
    if ($resultado) {
        // Redirigimos de vuelta a la matriz
        header("Location: ../views/admin/ver_matriz.php?sede=" . $id_sede_actual . "&msg=ok");
    } else {
        echo "<script>alert('Error al actualizar la base de datos.'); window.history.back();</script>";
    }
}
?>