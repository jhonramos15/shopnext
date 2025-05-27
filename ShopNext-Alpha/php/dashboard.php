<?php
require_once 'SessionManager.php';

$session = new SessionManager();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Panel</title></head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($session->getUserName()) ?>!</h1>
    <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
