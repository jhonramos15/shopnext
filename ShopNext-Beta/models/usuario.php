<?php
/**
 * Modelo Usuario: gestiona todas las operaciones de la base de datos
 * relacionadas con los usuarios y clientes.
 */
class Usuario {
    private $conn; // Almacenará la conexión a la BD

    /**
     * Constructor que recibe la conexión a la base de datos.
     * @param mysqli $db Objeto de conexión a la base de datos.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Verifica si un correo electrónico ya existe en la base de datos.
     *
     * @param string $correo El correo a verificar.
     * @return bool True si el correo existe, false en caso contrario.
     */
    public function correoExiste(string $correo): bool {
        $stmt = $this->conn->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        
        return $num_rows > 0;
    }

    /**
     * Crea un nuevo usuario y su perfil de cliente en la base de datos
     * utilizando una transacción para garantizar la integridad de los datos.
     *
     * @param array $datos Los datos del usuario a registrar.
     * @return bool True si el registro fue exitoso, lanza Exception si falla.
     */
    public function crearUsuario(array $datos): bool {
        // Iniciamos una transacción
        $this->conn->begin_transaction();

        try {
            // 1. Insertar en la tabla 'usuario'
            $stmt_usuario = $this->conn->prepare(
                "INSERT INTO usuario (correo_usuario, contraseña, rol, verificado, token_verificacion) VALUES (?, ?, ?, 0, ?)"
            );
            $stmt_usuario->bind_param("ssss", $datos['correo'], $datos['clave_hash'], $datos['rol'], $datos['token']);
            $stmt_usuario->execute();
            
            // Obtenemos el ID del usuario recién insertado
            $id_usuario = $this->conn->insert_id;
            $stmt_usuario->close();

            if (!$id_usuario) {
                throw new Exception("No se pudo obtener el ID del nuevo usuario.");
            }

            // 2. Insertar en la tabla 'cliente'
            $stmt_cliente = $this->conn->prepare(
                "INSERT INTO cliente (nombre, id_usuario) VALUES (?, ?)"
            );
            // Aquí deberías pasar el resto de datos como 'nombre', 'telefono', etc.
            $stmt_cliente->bind_param("si", $datos['nombre'], $id_usuario);
            $stmt_cliente->execute();
            $stmt_cliente->close();

            // Si todo fue bien, confirmamos los cambios
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Si algo falla, revertimos todos los cambios
            $this->conn->rollback();
            // Propagamos la excepción para que el controlador la capture
            throw $e;
        }
    }
}
?>