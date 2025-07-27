<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está logueado y tiene el rol de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /shopnext/public/index.php');
    exit();
}

// Opcional: Verifica la inactividad de la sesión
$inactive = 1800; // 30 minutos
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    header('Location: /shopnext/public/index.php?status=session_expired');
    exit();
}
$_SESSION['last_activity'] = time();

// Define las páginas permitidas
$allowed_admin_pages = [
    '/shopnext/ShopNext-Beta/views/dashboard/admin/adminView.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/productos.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/vendedores.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/clientes.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/ingresos.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/ayuda.php',
    '/shopnext/ShopNext-Beta/views/dashboard/admin/accountAdmin.php' 
];

// Obtiene la ruta del script actual
$current_page = $_SERVER['PHP_SELF'];

// --- INICIO DEL CÓDIGO DE DIAGNÓSTICO ---
// Comprobamos si la página actual está en la lista de páginas permitidas
if (!in_array($current_page, $allowed_admin_pages)) {
    // Si no está, en lugar de redirigir, mostramos qué estamos comparando.
    echo "<h1>Acceso Denegado por auth_guard.php</h1>";
    echo "<p><strong>Razón:</strong> La página actual no está en la lista de páginas permitidas.</p>";
    echo "<p><strong>Página actual (valor de \$_SERVER['PHP_SELF']):</strong></p>";
    echo "<pre>" . htmlspecialchars($current_page) . "</pre>";
    echo "<p><strong>Lista de páginas permitidas:</strong></p>";
    echo "<pre>";
    print_r($allowed_admin_pages);
    echo "</pre>";
    die(); // Detenemos la ejecución para que no redirija.
}
// --- FIN DEL CÓDIGO DE DIAGNÓSTICO ---
?>