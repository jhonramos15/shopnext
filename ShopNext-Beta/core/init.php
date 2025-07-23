<?php

// 1. Incluimos la clase SessionManager UNA SOLA VEZ.
require_once __DIR__ . '/sessionManager.php';

// 2. Creamos una instancia global de la clase.
// Ahora, en lugar de crear un 'new SessionManager()' en cada archivo,
// simplemente usaremos la variable $sessionManager que se crea aquí.
$sessionManager = new SessionManager();

?>