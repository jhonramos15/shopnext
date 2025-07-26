<?php

/**
 * Archivo de configuración central.
 * Define constantes para las credenciales de la base de datos,
 * PHPMailer y rutas principales de la aplicación.
 */

// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shopnexs');

// --- CONFIGURACIÓN DE PHPMailer (GMAIL) ---
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'shopnextnoreply@gmail.com');
define('MAIL_PASS', 'uhym jhjw dzym pyyf'); // Contraseña de aplicación de Gmail
define('MAIL_FROM_NAME', 'ShopNext No-Reply');

// RUTAS DE LA APLICACIÓN
// Define la URL base del proyecto. MUY IMPORTANTE para los enlaces.
// Cámbiala si tu proyecto está en otra ruta. Ejemplo: http://localhost/mi_proyecto
define('BASE_URL', 'http://localhost/ShopNext/ShopNext-Beta');

?>