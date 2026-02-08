<?php
// models/Usuario.php
// VERSIÓN SEGURA - SPRINT 2

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades exactas de tu Base de Datos
    public $id_usuario;
    public $nombre_completo;
    public $email;
    public $password;
    public $id_rol;
    public $id_sede;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        // 1. Buscamos el usuario por su email
        $query = "SELECT id_usuario, nombre_completo, password, id_rol, id_sede 
                  FROM " . $this->table_name . " 
                  WHERE email = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        // Limpiamos el email por seguridad
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        // 2. Si encontramos el email...
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. VERIFICACIÓN DE SEGURIDAD
            // Aquí comparamos la contraseña "123456" con el hash encriptado de la BD
            if (password_verify($this->password, $row['password'])) {
                
                // Si coinciden, llenamos los datos
                $this->id_usuario = $row['id_usuario'];
                $this->nombre_completo = $row['nombre_completo'];
                $this->id_rol = $row['id_rol'];
                $this->id_sede = $row['id_sede'];
                
                return true;
            }
        }
        // Si llegamos aquí, es que la contraseña o el email fallaron
        return false;
    }
}
?>