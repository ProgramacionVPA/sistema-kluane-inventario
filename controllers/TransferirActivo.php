<?php
session_start();
require_once '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_activo = $_POST['id_activo'];
    $id_sede_destino = $_POST['id_sede_destino']; // A dónde va (Ej: Quito)
    $motivo = $_POST['motivo']; // Ej: "Reparación", "Cambio de Proyecto"
    
    if(empty($id_activo) || empty($id_sede_destino)){
        echo "<script>alert('Error: Faltan datos.'); window.history.back();</script>";
        exit();
    }

    $database = new Conexion();
    $conn = $database->getConexion();

    try {
        // 1. MOVER EL ACTIVO
        // Al transferir, quitamos el responsable actual (se va a Bodega de la nueva sede)
        // y cambiamos la 'id_sede_actual'.
        $sql = "UPDATE activos SET 
                id_sede_actual = :sede_dest, 
                id_usuario_responsable = NULL,  -- Se queda sin dueño (En tránsito/Bodega)
                necesita_insumos = 'NO'         -- Reseteamos alertas
                WHERE id_activo = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sede_dest', $id_sede_destino);
        $stmt->bindParam(':id', $id_activo);
        
        if($stmt->execute()) {
            
            // 2. REGISTRAR EN HISTORIAL (Trazabilidad)
            // Guardamos que salió de la sede origen hacia la destino
            $sqlH = "INSERT INTO historial_movimientos (id_activo, id_usuario_responsable, tipo_movimiento, fecha_movimiento, ubicacion_destino, observacion) 
                     VALUES (:id_activo, :usuario, 'Transferencia / Devolucion', NOW(), :sede_nombre, :obs)";
            
            $stmtH = $conn->prepare($sqlH);
            $stmtH->bindParam(':id_activo', $id_activo);
            $stmtH->bindParam(':usuario', $_SESSION['id_usuario']); // Quien hizo el envío (Diego)
            $stmtH->bindParam(':sede_nombre', $id_sede_destino); // Guardamos el ID de destino
            $texto_obs = "Transferencia enviada por " . $_SESSION['nombre_completo'] . ". Motivo: " . $motivo;
            $stmtH->bindParam(':obs', $texto_obs);
            $stmtH->execute();

            // Éxito: Volver a la matriz con mensaje de éxito
            header("Location: ../views/admin/ver_matriz.php?sede=" . $_SESSION['id_sede'] . "&msg=transfer");
        } else {
            echo "Error al transferir.";
        }

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>