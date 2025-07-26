<?php
class Conexion {
    private $servidor = "localhost";
    private $usuario = "root";
    private $contrasena = ""; // Deja vacío si no usas contraseña en XAMPP
    private $base_de_datos = "shopnexs"; // Asegúrate que el nombre sea correcto
    private $charset = "utf8";
    public $conectar;

    public function __construct() {
        // Oculta errores de mysqli para manejarlos nosotros
        mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
        try {
            $this->conectar = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->base_de_datos);
            $this->conectar->set_charset($this->charset);
        } catch (mysqli_sql_exception $e) {
            // Muestra un error claro si la conexión falla
            die("Error crítico de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function getConexion() {
        return $this->conectar;
    }
}
?>