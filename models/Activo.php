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
        // Hacemos un JOIN para que no salgan números (id_sede), sino los nombres reales
        $query = "SELECT 
                    a.id_activo,
                    a.codigo_interno,
                    a.marca,
                    a.modelo,
                    a.serie,
                    a.estado,
                    c.nombre as categoria,
                    s.nombre as sede,
                    u.nombre_completo as responsable
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
            
            //Usuario que está logueado 
            
            $stmt->bindParam(":usuario", $_SESSION['usuario_id']);

            if($stmt->execute()) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
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

    // Función para ELIMINAR un activo por su ID
    public function eliminar($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_activo = :id";
            $stmt = $this->conn->prepare($query);
            
            // Limpieza básica de seguridad
            $id = htmlspecialchars(strip_tags($id));
            
            // Vinculamos el ID
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
        
        // Retorna una sola fila con los datos
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

            // Limpieza y asignación de valores
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
}
?>