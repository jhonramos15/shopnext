<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../auth/login.html");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 300;

// Verificar si existe el tiempo de última actividad
if (isset($_SESSION['last_activity'])) {
    $tiempo_inactivo = time() - $_SESSION['last_activity'];

    if ($tiempo_inactivo > $inactividad) {
        // Cierra la sesión si pasó el tiempo
        session_unset();
        session_destroy();
        header("Location: ../auth/login.php?mensaje=sesion_expirada");
        exit;
    } else {
        $_SESSION['last_activity'] = time(); // ✅ Refresca el tiempo de actividad
    }
} else {
    $_SESSION['last_activity'] = time(); // ✅ Inicializa el tiempo de actividad si no existía
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Ventas</title>
    <link rel="stylesheet" href="../../public/css/vendedor/dashboardVendedor.css">
    <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container">
            <img src="../../public/img/logo.svg" alt="Logo" class="logo-img">
        </div>
        <ul class="menu">
            <li class="active"><a href="vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li><a href="vendedor/productos.php"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li><a href="vendedor/pedidos.php"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="vendedor/subirProductos.php"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="vendedor/ingresos.php"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
        </ul>
        <div class="user-profile-container">
            <div class="user" id="userProfileBtn">
                <img src="https://i.pravatar.cc/40?u=brayan" alt="user" />
                <div class="user-info">
                    <p>Brayan</p>
                    <small>Administrador</small>
                </div>
                <i data-lucide="chevron-down" class="profile-arrow"></i>
            </div>
            <div class="profile-dropdown" id="profileDropdownMenu">
                <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
                <a href="#configuracion"><i data-lucide="settings"></i><span>Configuración</span></a>
                <a href="../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
            </div>
        </div>
    </aside>

    <main class="main">
        <header class="header">
            <h1>Hola, Brayan 👋</h1>
            <div class="header-search-container">
                <div class="input-icon header-search">
                    <i data-lucide="search"></i>
                    <input type="text" placeholder="Buscar..." />
                </div>
            </div>
        </header>

        <div class="dashboard-content">
            <section class="overview-cards">
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ingresos Totales</h3>
                        <p>$12,450 <span class="percentage positive">+15.2%</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="shopping-cart"></i>
                    <div>
                        <h3>Pedidos Realizados</h3>
                        <p>350 <span class="percentage positive">+21.0%</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="user-plus"></i>
                    <div>
                        <h3>Nuevos Clientes</h3>
                        <p>82 <span class="percentage neutral">+5.4%</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="activity"></i>
                    <div>
                        <h3>Tasa de Conversión</h3>
                        <p>4.25% <span class="percentage positive">+1.5%</span></p>
                    </div>
                </div>
            </section>
            
            <section class="grid-row top-row">
                <div class="card revenue-summary-card">
                    <div class="chart-header">
                        <h3>Resumen de Ingresos</h3>
                        <div class="chart-controls">
                            <button class="active">Mes</button>
                            <button>Semana</button>
                        </div>
                    </div>
                    <div class="chart-canvas-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="card orders-summary-card">
                    <h3>Pedidos por Día (Semana)</h3>
                    <div class="chart-canvas-container income-chart-container">
                        <canvas id="dailyOrdersChart"></canvas>
                    </div>
                </div>
            </section>

            <section class="grid-row middle-row">
                <div class="card recent-orders-card">
                    <h3>Pedidos Recientes</h3>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>NÚMERO DE SEGUIMIENTO</th>
                                    <th>PRODUCTO</th>
                                    <th>ESTADO</th>
                                    <th>IMPORTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>25/03/2024</td><td>Teclado</td><td><span class="status rejected">Rechazado</span></td><td>$70,999</td></tr>
                                <tr><td>25/03/2024</td><td>Accesorios</td><td><span class="status approved">Aprobado</span></td><td>$83,348</td></tr>
                                <tr><td>26/03/2024</td><td>Lente de cámara</td><td><span class="status rejected">Rechazado</span></td><td>$40,570</td></tr>
                                <tr><td>26/03/2024</td><td>TELEVISOR</td><td><span class="status pending">Pendiente</span></td><td>$410,780</td></tr>
                                <tr><td>26/03/2024</td><td>Auricular</td><td><span class="status approved">Aprobado</span></td><td>$10,239</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card category-sales-card">
                     <h3>Ventas por Categoría</h3>
                    <div class="chart-canvas-container analysis-chart-container">
                        <canvas id="categorySalesChart"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    <script src="../../public/js/vendedor/dashboardVendedor.js"></script>
</body>
</html>