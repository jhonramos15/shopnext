<?php
// Incluimos el SessionManager para usarlo directamente
require_once __DIR__ . '/../core/init.php';
require_once __DIR__ . '/../config/conexion.php'; // Para la conexión a la base de datos

$session = new SessionManager();

// Si el usuario ya está logueado, lo redirigimos
if ($session->isLoggedIn()) {
    header('Location: /shopnext/ShopNext-Beta/public/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $stmt = $db->prepare("SELECT id_usuario, nombre_usuario, contrasena FROM usuario WHERE correo_electronico = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificamos la contraseña
                if (password_verify($password, $user['contrasena'])) {
                    // ¡CORRECCIÓN! Usamos el método login del SessionManager
                    $session->login($user['id_usuario'], $user['nombre_usuario']);
                    header('Location: /shopnext/ShopNext-Beta/public/index.php');
                    exit;
                } else {
                    $error = "La contraseña es incorrecta.";
                }
            } else {
                $error = "No se encontró un usuario con ese correo electrónico.";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    }
}
?>