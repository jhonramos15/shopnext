<?php
class Database {
    // Propiedades de la conexión
    private static $host;
    private static $db_name;
    private static $username;
    private static $password;
    
    // Almacena la única instancia de la conexión (Singleton)
    private static $conn = null;

     //El constructor es privado para prevenir la creación de múltiples instancias.

    private function __construct() {
        // Este método está vacío intencionalmente.
    }

    /**
     * Método estático para obtener la instancia única de la conexión a la BD.
     *
     * @return mysqli|null El objeto de conexión mysqli o null si falla.
     */
    public static function getConnection() {
        // Si la conexión aún no ha sido creada, la crea.
        if (self::$conn === null) {
            // Incluimos las constantes de configuración solo una vez
            require_once __DIR__ . '/../core/config.php';

            self::$host     = DB_HOST;
            self::$db_name  = DB_NAME;
            self::$username = DB_USER;
            self::$password = DB_PASS;
            
            try {
                // Creamos la conexión
                self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$db_name);
                
                // Forzamos el uso de UTF-8 para evitar problemas con tildes y ñ
                self::$conn->set_charset("utf8mb4");

            } catch (Exception $e) {
                // En un entorno de producción, sería mejor registrar el error en un log
                // en lugar de mostrarlo en pantalla por seguridad.
                die("Error de conexión: " . $e->getMessage());
            }
        }
        
        // Devolvemos la conexión existente o la recién creada.
        return self::$conn;
    }

    /**
     * Previene la clonación de la instancia (parte del patrón Singleton).
     */
    private function __clone() {}

    /**
     * Previene la deserialización de la instancia (parte del patrón Singleton).
     */
    public function __wakeup() {}
}
?>