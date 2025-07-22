<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'shopnexs';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Al crear el objeto, se conecta a la BD
    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('set names utf8');
        } catch(PDOException $exception) {
            echo 'Error de Conexión: ' . $exception->getMessage();
        }
    }

    // Método para obtener la conexión PDO
    public function getConnection() {
        return $this->conn;
    }
}
?>