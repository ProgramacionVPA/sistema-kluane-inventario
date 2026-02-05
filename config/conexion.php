<?php
class Conexion {
    // Datos del servidor
    private $host = "localhost";
    private $db_name = "kluane_inventario";
    private $username = "root";
    private $password = ""; 
    public $conn;

    // Método para conectar
    public function getConexion() {
        $this->conn = null;

        try {
            // Conexión usando PDO (forma segura)
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configurar para que nos avise si hay error y acepte tildes/eñes
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Si quieres probar si funciona, descomenta la linea de abajo:
            // echo "¡Conexión Exitosa a la Base de Datos KLUANE!";
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

// PRUEBA RÁPIDA (Solo para verificar que la conexión funciona)
// $prueba = new Conexion();
// $prueba->getConexion();
?>