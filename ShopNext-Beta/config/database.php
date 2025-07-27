<?php
/**
 * Clase Database: Gestiona la conexión a la base de datos.
 * Se encarga de establecer y proporcionar la conexión, manteniendo
 * las credenciales en un solo lugar.
 */
class Database {
    // Parámetros de conexión
    private $host = 'localhost';
    private $db_name = 'shopnexs'; // <-- Revisa que sea el nombre correcto de tu BD
    private $username = 'root';
    private $password = ''; // <-- Si tienes contraseña, ponla aquí
    public $conn;

    /**
     * Obtiene la conexión a la base de datos.
     * @return mysqli|null Objeto de conexión mysqli o null si falla.
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            // En un entorno de producción, nunca muestres errores detallados al usuario.
            // Guárdalos en un log.
            error_log($e->getMessage());
            return null;
        }

        return $this->conn;
    }
}
?>