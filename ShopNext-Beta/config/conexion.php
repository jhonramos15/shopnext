<?php
class Database {
    private $host = "localhost";
    private $db_name = "shopnexs"; // Corregí el nombre de 'shopnext' a 'shopnexs' como en tu archivo.
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Esta es la conexión PDO que usaremos en todo el proyecto
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            // En lugar de solo mostrar el error, detenemos la ejecución para evitar problemas de seguridad.
            die("Error de conexión: No se pudo conectar a la base de datos. " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>