<?php
require_once __DIR__ . '/../config/conexion.php';

class Activo {
    private $conn;
    private $table_name = "activos";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Función para LEER todo el inventario (Matriz 07)
    public function leerTodo() {
        // Hacemos un JOIN y añadimos una subconsulta para contar los movimientos (Auditoría Estricta)
        $query = "SELECT 
                    a.id_activo,
                    a.codigo_interno,
                    a.marca,
                    a.modelo,
                    a.serie,
                    a.estado,
                    c.nombre as categoria,
                    s.nombre as sede,
                    u.nombre_completo as responsable,
                    (SELECT COUNT(*) FROM historial_movimientos h WHERE h.id_activo = a.id_activo) as total_movimientos
                  FROM " . $this->table_name . " a
                  LEFT JOIN categorias c ON a.id_categoria = c.id_categoria
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
                  ORDER BY a.id_activo DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Función para GUARDAR un nuevo activo en la BD
    public function crear($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (codigo_interno, serie, marca, modelo, estado, id_categoria, id_sede_actual, id_usuario_responsable) 
                    VALUES 
                    (:codigo, :serie, :marca, :modelo, :estado, :categoria, :sede, :usuario)";

            $stmt = $this->conn->prepare($query);

            // Limpiar los datos
            $codigo = htmlspecialchars(strip_tags($datos['codigo']));
            $serie = htmlspecialchars(strip_tags($datos['serie']));
            $marca = htmlspecialchars(strip_tags($datos['marca']));
            $modelo = htmlspecialchars(strip_tags($datos['modelo']));

            // Vincular los valores
            $stmt->bindParam(":codigo", $codigo);
            $stmt->bindParam(":serie", $serie);
            $stmt->bindParam(":marca", $marca);
            $stmt->bindParam(":modelo", $modelo);
            $stmt->bindParam(":estado", $datos['estado']);
            $stmt->bindParam(":categoria", $datos['categoria']);
            $stmt->bindParam(":sede", $datos['sede']);
            
            // CORRECCIÓN: Un equipo nuevo entra a Bodega (Sin Asignar), responsable es NULL.
            $usuario_responsable = null;
            $stmt->bindParam(":usuario", $usuario_responsable, PDO::PARAM_NULL);

            if($stmt->execute()) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            // VERIFICAMOS SI EL ERROR ES POR DUPLICADO (SQLSTATE 23000)
            if ($e->getCode() == 23000) {
                return "DUPLICADO"; 
            }
            // Si es otro error raro, retorna falso
            return false;
        }
    }

    // Función para contar cuántos equipos tenemos en total
    public function contarTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Función para ELIMINAR un activo por su ID (Mantenemos la regla estricta de base de datos)
    public function eliminar($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_activo = :id";
            $stmt = $this->conn->prepare($query);
            
            $id = htmlspecialchars(strip_tags($id));
            $stmt->bindParam(":id", $id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Función para obtener los datos de UN solo activo (para editar)
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_activo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para ACTUALIZAR (Update) los datos de un activo
    public function actualizar($datos) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                    SET codigo_interno = :codigo,
                        serie = :serie,
                        marca = :marca,
                        modelo = :modelo,
                        estado = :estado
                    WHERE id_activo = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":codigo", htmlspecialchars(strip_tags($datos['codigo'])));
            $stmt->bindParam(":serie", htmlspecialchars(strip_tags($datos['serie'])));
            $stmt->bindParam(":marca", htmlspecialchars(strip_tags($datos['marca'])));
            $stmt->bindParam(":modelo", htmlspecialchars(strip_tags($datos['modelo'])));
            $stmt->bindParam(":estado", htmlspecialchars(strip_tags($datos['estado'])));
            $stmt->bindParam(":id", htmlspecialchars(strip_tags($datos['id_activo'])));

            if($stmt->execute()) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Función para asignar responsable Y cambiar de sede al mismo tiempo
    public function asignar($id_activo, $id_usuario, $id_sede, $observacion) {
        try {
            // 1. Actualizar tabla ACTIVOS
            $query = "UPDATE activos SET 
                      id_usuario_responsable = :id_usuario, 
                      id_sede_actual = :id_sede,     -- Aquí guardamos la nueva sede
                      estado = 'Operativo',          -- Asumimos que si se asigna, está operativo
                      necesita_insumos = 'NO'        -- Reseteamos alertas
                      WHERE id_activo = :id_activo";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_usuario", $id_usuario);
            $stmt->bindParam(":id_sede", $id_sede);
            $stmt->bindParam(":id_activo", $id_activo);
            $stmt->execute();

            // 2. Guardar en HISTORIAL
            $queryH = "INSERT INTO historial_movimientos 
                       (id_activo, id_usuario_responsable, tipo_movimiento, fecha_movimiento, ubicacion_destino, observacion) 
                       VALUES (:id_activo, :id_usuario, 'Asignacion', NOW(), :id_sede, :obs)";
            
            $stmtH = $this->conn->prepare($queryH);
            $stmtH->bindParam(":id_activo", $id_activo);
            $stmtH->bindParam(":id_usuario", $id_usuario);
            $stmtH->bindParam(":id_sede", $id_sede); // Guardamos el ID de la sede en ubicación
            $stmtH->bindParam(":obs", $observacion);
            $stmtH->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Función para ver la VIDA del activo (Historial)
    public function obtenerHistorial($id_activo) {
        $query = "SELECT h.*, u.nombre_completo, u.email 
                  FROM historial_movimientos h
                  INNER JOIN usuarios u ON h.id_usuario_responsable = u.id_usuario
                  WHERE h.id_activo = :id
                  ORDER BY h.fecha_asignacion DESC"; 
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_activo);
        $stmt->execute();
        
        return $stmt;
    }
    
    // 2. Contar activos por Estado (Para el gráfico de Pastel)
    public function contarPorEstado() {
        $query = "SELECT estado, COUNT(*) as cantidad FROM " . $this->table_name . " GROUP BY estado";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Contar activos por Sede (Para el gráfico de Barras)
    public function contarPorSede() {
        $query = "SELECT s.nombre as sede, COUNT(a.id_activo) as cantidad 
                  FROM " . $this->table_name . " a
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  GROUP BY s.id_sede";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================================
    // NUEVAS FUNCIONES DE REFACTORIZACIÓN MVC (Para limpiar las Vistas)
    // =========================================================================

    public function obtenerTodosUsuarios() {
        $query = "SELECT * FROM usuarios ORDER BY nombre_completo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodasSedes() {
        $query = "SELECT * FROM sedes ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para LEER solo los equipos asignados a un colaborador específico
    public function leerPorUsuario($id_usuario) {
        $query = "SELECT 
                    a.id_activo,
                    a.codigo_interno,
                    a.marca,
                    a.modelo,
                    a.serie,
                    a.estado,
                    c.nombre as categoria,
                    s.nombre as sede,
                    u.nombre_completo as responsable,
                    (SELECT COUNT(*) FROM historial_movimientos h WHERE h.id_activo = a.id_activo) as total_movimientos
                  FROM " . $this->table_name . " a
                  LEFT JOIN categorias c ON a.id_categoria = c.id_categoria
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
                  WHERE a.id_usuario_responsable = :id_usuario
                  ORDER BY a.id_activo DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    // Función para obtener TODOS los detalles de un activo (con JOINs) para reportes/PDFs
    public function obtenerDetallesPorId($id) {
        $query = "SELECT a.*, 
                  u.nombre_completo as responsable, u.email,
                  s.nombre as sede, 
                  c.nombre as categoria 
                  FROM " . $this->table_name . " a
                  LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
                  LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
                  LEFT JOIN categorias c ON a.id_categoria = c.id_categoria
                  WHERE a.id_activo = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>