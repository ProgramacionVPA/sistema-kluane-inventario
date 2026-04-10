<?php
require_once __DIR__ . '/../config/conexion.php';

class Sede {
    private $conn;
    private $table_name = "sedes";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Leer todas las sedes (Para la tabla del Administrador)
    public function leerTodo() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY estado ASC, nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer SOLO las sedes activas (Para los menús desplegables)
    public function leerActivas() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 'Activo' ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear nueva sede
    public function crear($nombre) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, estado) VALUES (:nombre, 'Activo')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        return $stmt->execute();
    }

    // Actualizar sede
    public function actualizar($id_sede, $nombre) {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre WHERE id_sede = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":id", $id_sede);
        return $stmt->execute();
    }

    // BORRADO LÓGICO: Apagar o Encender un proyecto
    public function cambiarEstado($id_sede, $nuevo_estado) {
        $query = "UPDATE " . $this->table_name . " SET estado = :estado WHERE id_sede = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $nuevo_estado);
        $stmt->bindParam(":id", $id_sede);
        return $stmt->execute();
    }
}
?>