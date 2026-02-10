<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades
    public $id_usuario;
    public $nombre_completo;
    public $email;
    public $password;
    public $id_rol;
    public $id_sede;
    public $area;     // Asegúrate de tener esta propiedad
    public $cargo;    // Y esta si la usas

    public function __construct($db = null) {
        if ($db) {
            $this->conn = $db;
        } else {
            $database = new Conexion();
            $this->conn = $database->getConexion();
        }
    }

    // --- LOGIN ---
    public function login() {
        $query = "SELECT id_usuario, nombre_completo, password, id_rol, id_sede 
                  FROM " . $this->table_name . " 
                  WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ESTA ES LA LÍNEA CLAVE: password_verify
            // Compara la contraseña escrita con el Hash de la BD
            if (password_verify($this->password, $row['password'])) {
                $this->id_usuario = $row['id_usuario'];
                $this->nombre_completo = $row['nombre_completo'];
                $this->id_rol = $row['id_rol'];
                $this->id_sede = $row['id_sede'];
                return true;
            }
        }
        return false;
    }

    // LEER TODOS (Para la lista)
    public function leerTodo() {
        $query = "SELECT u.*, r.nombre_rol as rol, s.nombre as sede 
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.id_rol = r.id_rol
                  LEFT JOIN sedes s ON u.id_sede = s.id_sede
                  ORDER BY u.nombre_completo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREAR USUARIO (Encripta la clave antes de guardar)
    public function crear($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                    (nombre_completo, email, password, id_rol, id_sede, area)
                    VALUES (:nombre, :email, :pass, :rol, :sede, :area)";
            
            $stmt = $this->conn->prepare($query);

            // ENCRIPTAMOS LA CLAVE
            $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":email", $datos['email']);
            $stmt->bindParam(":pass", $password_hash);
            $stmt->bindParam(":rol", $datos['rol']);
            $stmt->bindParam(":sede", $datos['sede']);
            $stmt->bindParam(":area", $datos['area']);

            if($stmt->execute()){ return true; }
            return false;

        } catch(PDOException $e) {
            if ($e->getCode() == 23000) return "DUPLICADO";
            return false;
        }
    }

    // ACTUALIZAR USUARIO
    public function actualizar($datos) {
        try {
            $sqlPass = "";
            if (!empty($datos['password'])) {
                $sqlPass = ", password = :pass";
            }
            $query = "UPDATE " . $this->table_name . "
                    SET nombre_completo = :nombre, email = :email, id_rol = :rol, 
                        id_sede = :sede, area = :area" . $sqlPass . "
                    WHERE id_usuario = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $datos['nombre']);
            $stmt->bindParam(":email", $datos['email']);
            $stmt->bindParam(":rol", $datos['rol']);
            $stmt->bindParam(":sede", $datos['sede']);
            $stmt->bindParam(":area", $datos['area']);
            $stmt->bindParam(":id", $datos['id_usuario']);

            if (!empty($datos['password'])) {
                $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(":pass", $password_hash);
            }

            if($stmt->execute()){ return true; }
            return false;
        } catch(PDOException $e) { return false; }
    }

    // ELIMINAR USUARIO
    public function eliminar($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            if($stmt->execute()){ return true; }
            return false;
        } catch (PDOException $e) { return false; }
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerRoles() {
        $stmt = $this->conn->prepare("SELECT * FROM roles"); $stmt->execute(); return $stmt;
    }
    public function obtenerSedes() {
        $stmt = $this->conn->prepare("SELECT * FROM sedes"); $stmt->execute(); return $stmt;
    }
}
?>