<?php
require_once 'SessionManager.php';

$session = new SessionManager();

// Simulación de autenticación (normalmente usarías base de datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === '1234') {
        $session->login(1, $user);
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($_GET['timeout'])) echo "<p>Sesión expirada, vuelve a iniciar sesión.</p>"; ?>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        Usuario: <input type="text" name="username" required><br>
        Clave: <input type="password" name="password" required><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
