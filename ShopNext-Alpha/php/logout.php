<?php
require_once 'SessionManager.php';

$session = new SessionManager();
$session->logout();

header("Location: proceso-login.php");
exit;
