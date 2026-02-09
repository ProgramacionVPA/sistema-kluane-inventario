<?php
require_once __DIR__ . '/../config/conexion.php';

class Matriz {
    private $conn;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Función para replicar el Excel de Campamento
    public function obtenerDatosMatriz($id_sede) {
        $query = "SELECT 
                    a.codigo_interno,
                    c.nombre as equipo,          
                    a.serie,                     
                    u.nombre_completo as responsable, 
                    u.area,                      
                    h.fecha_asignacion,          
                    s.nombre as ubicacion,       
                    a.estado                     
                  FROM activos a
                  -- Unimos con Categorias para saber si es Laptop o Radio
                  INNER JOIN categorias c ON a.id_categoria = c.id_categoria
                  -- Unimos con Usuarios para sacar el AREA y Nombre
                  LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
                  -- Unimos con Sedes para el filtro
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  -- Subconsulta para obtener la ÚLTIMA fecha de asignación real
                  LEFT JOIN (
                        SELECT id_activo, MAX(fecha_asignacion) as fecha_asignacion
                        FROM historial_movimientos
                        WHERE tipo_movimiento = 'Asignacion'
                        GROUP BY id_activo
                  ) h ON a.id_activo = h.id_activo
                  
                  -- FILTRO CRÍTICO: Solo mostrar equipos de la Sede seleccionada
                  WHERE a.id_sede_actual = :sede
                  ORDER BY u.area ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sede", $id_sede);
        $stmt->execute();
        return $stmt;
    }

    // Para llenar el select de proyectos
    public function obtenerSedes() {
        $query = "SELECT * FROM sedes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>