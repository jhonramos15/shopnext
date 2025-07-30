<?php
// Carga la conexión a la base de datos
require_once __DIR__ . '/../../config/database.php';

// Define la URL raíz de tu proyecto. ¡Esta es la clave!
define('BASE_URL', '/shopnext/ShopNext-Beta/public/');

// Carga y activa el autoloader. No necesitamos más require_once para los modelos.
require_once __DIR__ . '/autoloader.php';
?>