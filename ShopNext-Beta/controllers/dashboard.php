<?php
require_once '../core/sessionManager.php';

$session = new SessionManager();

if (!$session->isLoggedIn()) {
    header("Location: ../views/auth/login.php");
    exit;
}
?>
