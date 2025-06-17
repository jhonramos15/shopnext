<?php
session_start();
session_unset();
session_destroy();
header("Location: ../views/auth/login.php?mensaje=logout_exitosa");
exit;
?>
