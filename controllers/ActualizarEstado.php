<?php
session_start();
require_once '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_activo = $_POST['id_activo'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $id_nuevo_responsable = $_POST['id_responsable']; 
    $necesita_insumos = $_POST['necesita_insumos']; 
    $observacion = $_POST['observacion'];

    $database = new Conexion();
    $conn = $database->getConexion();

try {
        // 1. Actualiza el Activo 
        $sql = "UPDATE activos SET 
                estado = :estado, 
                id_usuario_responsable = :resp, 
                necesita_insumos = :insumos 
                WHERE id_activo = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':resp', $id_nuevo_responsable);
        $stmt->bindParam(':insumos', $necesita_insumos);
        $stmt->bindParam(':id', $id_activo);
        
        if($stmt->execute()) {
            
            
            $sqlH = "INSERT INTO historial_movimientos (id_activo, id_usuario_responsable, tipo_movimiento, fecha_movimiento, ubicacion_destino, observacion) 
                     VALUES (:id_activo, :id_usuario, 'Edicion en Campo', NOW(), :sede, :obs)";
            
            $stmtH = $conn->prepare($sqlH);
            $stmtH->bindParam(':id_activo', $id_activo);
            $stmtH->bindParam(':id_usuario', $_SESSION['id_usuario']); 
            $stmtH->bindParam(':sede', $_SESSION['id_sede']);
            $texto_obs = "Cambio realizado en sitio. " . $observacion;
            $stmtH->bindParam(':obs', $texto_obs);
            $stmtH->execute();

            
            header("Location: ../views/admin/ver_matriz.php?sede=" . $_SESSION['id_sede'] . "&msg=ok");
        } else {
            echo "Error al actualizar.";
        }

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>