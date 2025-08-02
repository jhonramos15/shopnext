<?php
// En src/config/Database.php

namespace Config;

use mysqli;
use mysqli_sql_exception; // Importante para capturar errores de SQL

class Database {
    private $host = 'localhost';
    private $db_name = 'shopnexs'; // ¿Estás 100% seguro de que se llama así?
    private $username = 'root';
    private $password = '';
    public $conn = null;

    public function getConnection() {
        // Si ya tenemos una conexión, la reutilizamos
        if ($this->conn) {
            return $this->conn;
        }

        // ✅ ¡AQUÍ ESTÁ LA MAGIA!
        // Le decimos a mysqli que sea "estricto" y que lance un error visible si algo falla.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Si el código llega aquí, la conexión fue exitosa.
            return $this->conn;

        } catch (mysqli_sql_exception $e) {
            // Si la conexión falla, el catch lo atrapará y detendremos todo
            // mostrando un mensaje de error claro y directo.
            die("<h1>Error Crítico de Base de Datos</h1><p>No se pudo conectar a MySQL. Revisa los datos.</p><p><strong>Error exacto:</strong> " . $e->getMessage() . "</p>");
        }
        
        return null; // Este return ya no debería alcanzarse
    }
}
?>