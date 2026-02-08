<?php
// models/Usuario.php

class Usuario {
    private $conn;
    private $table_name = "usuarios"; // Asegúrate que tu tabla en la BD se llame así

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para verificar el login
    public function login() {
        // Consulta para buscar el usuario por email
        $query = "SELECT id, nombre, password, rol FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        // Preparar la declaración
        $stmt = $this->conn->prepare($query);

        // Limpiar datos (seguridad)
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Vincular el email al parámetro ?
        $stmt->bindParam(1, $this->email);

        // Ejecutar la consulta
        $stmt->execute();

        // Si encontramos el usuario
        if ($stmt->rowCount() > 0) {
            // Obtener los datos (fetch)
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña (usando password_verify para hashes o comparación directa si aún no usas hash)
            // NOTA: Para este prototipo asumiremos que las contraseñas están hasheadas. 
            // Si en tu BD están en texto plano, cambia esto por: if ($this->password == $row['password'])
            if (password_verify($this->password, $row['password'])) {
                // Asignar valores al objeto
                $this->id = $row['id'];
                $this->nombre = $row['nombre'];
                $this->rol = $row['rol'];
                return true;
            }
        }
        return false;
    }
}
?>