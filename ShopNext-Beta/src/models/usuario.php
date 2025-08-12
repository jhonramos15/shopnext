<?php
/**
 * Modelo Usuario: gestiona todas las operaciones de la base de datos
 * relacionadas con los usuarios (clientes y vendedores).
 */

namespace App\Models;

// Es importante importar las clases que se usarán, como la de la base de datos
// y las excepciones para un manejo de errores más específico.
use Config\Database;
use Exception;
use mysqli;

class Usuario {
    private $conn; // Almacenará la conexión a la BD

    /**
     * El constructor ahora obtiene la conexión a la base de datos por sí mismo.
     * Esto simplifica la creación de instancias en los controladores.
     */
    public function __construct() {
        // Crea una instancia de la clase Database para obtener la conexión.
        $database = new Database(); // Usamos Database para referirnos a la clase en el namespace global.
        $this->conn = $database->getConnection();

        // Si la conexión falla, es crucial detenerse para evitar más errores.
        if ($this->conn === null) {
            // En un entorno real, esto debería registrar un error crítico.
            // Para depuración, podemos mostrar un mensaje y detener.
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    /**
     * Verifica si un correo electrónico ya existe en la tabla 'usuario'.
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
     * Verifica si un número de teléfono ya existe en la tabla 'cliente' o 'vendedor'.
     * NOTA: Sería ideal tener el teléfono en la tabla 'usuario' para evitar buscar en dos tablas.
     * Por ahora, lo buscamos en 'cliente'.
     *
     * @param string $telefono El teléfono a verificar.
     * @return bool True si el teléfono existe, false en caso contrario.
     */
    public function telefonoExiste(string $telefono): bool {
        // Asumimos que el teléfono de un vendedor también se valida aquí.
        // La consulta original era a una tabla 'usuarios' que parece no existir en el esquema final.
        // Adaptamos la consulta a la tabla 'cliente'.
        $stmt = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE telefono = ?");
        $stmt->bind_param("s", $telefono);
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
        public function crearCliente(array $datos)
{
    // Limpieza de datos
    $nombre = htmlspecialchars(strip_tags($datos['nombre']));
    $telefono = htmlspecialchars(strip_tags($datos['telefono']));
    $direccion = htmlspecialchars(strip_tags($datos['direccion']));
    $genero = htmlspecialchars(strip_tags($datos['genero']));
    $fecha_nacimiento = htmlspecialchars(strip_tags($datos['fecha_nacimiento']));
    $correo = htmlspecialchars(strip_tags($datos['correo']));
    $clave = $datos['clave'];
    
    $token = bin2hex(random_bytes(16));
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    $this->conn->begin_transaction();

    try {
        // --- Insertar en la tabla 'usuario' ---
        $stmt_usuario = $this->conn->prepare(
            "INSERT INTO usuario (correo_usuario, contrasena, token, rol) VALUES (?, ?, ?, 'cliente')"
        );
        // Si la preparación falla, la consulta está mal escrita
        if ($stmt_usuario === false) {
            throw new Exception("Error al preparar la consulta de usuario: " . $this->conn->error);
        }
        $stmt_usuario->bind_param("sss", $correo, $clave_hash, $token);
        
        // Si la ejecución falla, los datos son incorrectos
        if (!$stmt_usuario->execute()) {
            throw new Exception("Error al ejecutar la consulta de usuario: " . $stmt_usuario->error);
        }
        
        $id_usuario = $this->conn->insert_id;
        $stmt_usuario->close();

        if (!$id_usuario) {
            throw new Exception("No se pudo obtener el ID del nuevo usuario.");
        }

        // --- Insertar en la tabla 'cliente' ---
        $stmt_cliente = $this->conn->prepare(
            "INSERT INTO cliente (nombre, telefono, direccion, genero, fecha_nacimiento, id_usuario, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        if ($stmt_cliente === false) {
            throw new Exception("Error al preparar la consulta de cliente: " . $this->conn->error);
        }
        $stmt_cliente->bind_param("sssssi", $nombre, $telefono, $direccion, $genero, $fecha_nacimiento, $id_usuario);
        
        if (!$stmt_cliente->execute()) {
            throw new Exception("Error al ejecutar la consulta de cliente: " . $stmt_cliente->error);
        }
        $stmt_cliente->close();

        $this->conn->commit();
        return $token;

    } catch (Exception $e) {
        $this->conn->rollback();
        // En lugar de guardar el error en el log, lo lanzamos para que el controlador lo atrape
        // Esto es solo para depurar, luego lo podemos quitar.
        throw new Exception($e->getMessage());
    }
}
    /**
     * Crea un nuevo usuario y su perfil de vendedor en la base de datos
     * utilizando una transacción.
     *
     * @param array $datos Los datos del vendedor a registrar.
     * @return bool True si el registro fue exitoso, false si no.
     */
public function crearVendedor(array $datos): bool
{
    // Limpiamos los datos que vienen del formulario
    $nombre = htmlspecialchars(strip_tags($datos['nombre']));
    $telefono = htmlspecialchars(strip_tags($datos['telefono']));
    $direccion = htmlspecialchars(strip_tags($datos['direccion']));
    $password = $datos['password'];

    // ✅ PASO 1: GENERAMOS EL TOKEN Y LA CONTRASEÑA (COMO EN crearCliente)
    $token = bin2hex(random_bytes(16)); // Genera el token obligatorio
    $clave_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Creamos un correo único para el vendedor para no chocar con los de clientes
    $correo_ficticio = $telefono . '@vendedor.shopnext';

    $this->conn->begin_transaction();

    try {
        // ✅ PASO 2: AÑADIMOS EL TOKEN A LA CONSULTA DEL USUARIO
        // La consulta ahora incluye las 4 columnas obligatorias: correo, contraseña, token y rol.
        $stmt_usuario = $this->conn->prepare(
            "INSERT INTO usuario (correo_usuario, contrasena, token, rol) VALUES (?, ?, ?, 'vendedor')"
        );
        if ($stmt_usuario === false) {
            throw new Exception("Error al preparar la consulta de usuario: " . $this->conn->error);
        }
        
        // El bind_param ahora tiene 3 's' porque enviamos 3 strings
        $stmt_usuario->bind_param("sss", $correo_ficticio, $clave_hash, $token);
        
        if (!$stmt_usuario->execute()) {
            throw new Exception("Error al ejecutar la consulta de usuario: " . $stmt_usuario->error);
        }
        
        $id_usuario = $this->conn->insert_id;
        $stmt_usuario->close();

        if (!$id_usuario) {
            throw new Exception("No se pudo obtener el ID del nuevo usuario para el vendedor.");
        }

        // --- Insertar en la tabla 'vendedor' (Esta parte estaba bien) ---
        $stmt_vendedor = $this->conn->prepare(
            "INSERT INTO vendedor (nombre_vendedor, telefono, direccion, id_usuario) VALUES (?, ?, ?, ?)"
        );
        if ($stmt_vendedor === false) {
            throw new Exception("Error al preparar la consulta de vendedor: " . $this->conn->error);
        }
        
        $stmt_vendedor->bind_param("sssi", $nombre, $telefono, $direccion, $id_usuario);
        
        if (!$stmt_vendedor->execute()) {
            throw new Exception("Error al ejecutar la consulta de vendedor: " . $stmt_vendedor->error);
        }
        $stmt_vendedor->close();

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        // Dejamos esto para que, si algo más falla, te diga exactamente qué es.
        throw new Exception($e->getMessage());
}
    }

    /**
     * Autentica a un usuario y determina su rol.
     * Esta es la función completamente reparada.
     *
     * @param string $correo El correo del usuario.
     * @param string $clave La contraseña en texto plano.
     * @return array|false Los datos del usuario (incluyendo el rol) o false si falla.
     */
    /**
 * VERSIÓN DE DEPURACIÓN FINAL
 * Esta función nos dirá exactamente dónde está el error.
 */
public function autenticarUsuario(string $correo, string $clave)
{
    // Preparamos UNA consulta que trae TODO: id, hash, estado y el ROL.
    $stmt = $this->conn->prepare(
        "SELECT id_usuario, contrasena, verificado, rol FROM usuario WHERE correo_usuario = ?"
    );
    
    if ($stmt === false) {
        // En un entorno real, aquí registrarías el error en un log.
        // error_log('Error al preparar la consulta: ' . $this->conn->error);
        return false;
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($clave, $usuario['contrasena'])) {
            // Éxito. Devolvemos el array del usuario.
            unset($usuario['contrasena']); // Quitamos el hash por seguridad.
            return $usuario;
        }
    }

    // Falla si no encuentra usuario o la clave es incorrecta.
    return false; 
}
    private function obtenerRol(int $id_usuario): ?string
    {
        $stmt_cliente = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
        $stmt_cliente->bind_param("i", $id_usuario);
        $stmt_cliente->execute();
        if ($stmt_cliente->get_result()->num_rows > 0) {
            $stmt_cliente->close();
            return 'cliente';
        }
        $stmt_cliente->close();

        $stmt_vendedor = $this->conn->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
        $stmt_vendedor->bind_param("i", $id_usuario);
        $stmt_vendedor->execute();
        if ($stmt_vendedor->get_result()->num_rows > 0) {
            $stmt_vendedor->close();
            return 'vendedor';
        }
        $stmt_vendedor->close();
        return null;
    }

}