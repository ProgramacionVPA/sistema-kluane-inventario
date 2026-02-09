<?php
require_once __DIR__ . '/../config/conexion.php';

class Matriz {
    private $conn;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function obtenerDatosMatriz($id_sede) {
        $query = "SELECT 
                    a.id_activo,
                    a.codigo_interno,
                    c.nombre as equipo,          
                    a.serie,                     
                    u.id_usuario as id_responsable, -- Necesitamos el ID para pre-seleccionar en el modal
                    u.nombre_completo as responsable, 
                    u.area,                      
                    h.fecha_asignacion,          
                    s.nombre as ubicacion,       
                    a.estado,
                    a.necesita_insumos              -- NUEVO CAMPO
                  FROM activos a
                  INNER JOIN categorias c ON a.id_categoria = c.id_categoria
                  LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  LEFT JOIN (
                        SELECT id_activo, MAX(fecha_asignacion) as fecha_asignacion
                        FROM historial_movimientos
                        WHERE tipo_movimiento = 'Asignacion'
                        GROUP BY id_activo
                  ) h ON a.id_activo = h.id_activo
                  WHERE a.id_sede_actual = :sede
                  ORDER BY u.area ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sede", $id_sede);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerSedes() {
        $query = "SELECT * FROM sedes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // NUEVA FUNCIÓN: Para llenar el select de "Nuevo Responsable"
    public function obtenerUsuarios() {
        $query = "SELECT id_usuario, nombre_completo, area FROM usuarios ORDER BY nombre_completo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>