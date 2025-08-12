<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNext | Dashboard Vendedor</title>
    <link rel="stylesheet" href="css/vendedor/dashboard-vendedor.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="img/logo.svg" alt="Logo" class="logo-img">
        </div>
        <ul class="menu">
            <li class="active"><a href="vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li><a href="index.php?action=seller&page=productos"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li><a href="index.php?action=seller&page=orders"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="index.php?action=seller&page=subir-productos"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="index.php?action=seller&page=income"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
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
                <a href="?action=logout"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
            </div>
        </div>
    </aside>

    <main class="main">
        <header class="header">
            <h1>Hola, <?php echo htmlspecialchars(explode(' ', $data['nombre_vendedor'])[0]); ?> 👋</h1>
        </header>
        <div class="dashboard-content">
            <section class="overview-cards">
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ingresos Totales</h3>
                        <p>$<?php echo number_format($data['ingresos_totales'], 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="shopping-cart"></i>
                    <div>
                        <h3>Pedidos Realizados</h3>
                        <p><?php echo number_format($data['pedidos_realizados']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="user-plus"></i>
                    <div>
                        <h3>Clientes Únicos</h3>
                        <p><?php echo number_format($data['nuevos_clientes']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="activity"></i>
                    <div>
                        <h3>Tasa de Conversión</h3>
                        <p>N/A</p> </div>
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
    <script src="js/vendedor/dashboard-vendedor.js"></script>
</body>
</html>