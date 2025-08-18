<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Inicio</title>
    <link rel="stylesheet" href="css/admin/dashboard-admin.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container">
            <img src="img/logo.svg" alt="Logo" class="logo-img">
        </div>
        <ul class="menu">
            <li class="active"><a href="index.php?action=admin&page=dashboard"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
            <li><a href="index.php?action=admin&page=products"><i data-lucide="box"></i><span>Productos</span></a></li>
            <li><a href="index.php?action=admin&page=clients"><i data-lucide="users"></i><span>Clientes</span></a></li>
            <li><a href="index.php?action=admin&page=income"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
            <li><a href="index.php?action=admin&page=help"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
            <li><a href="index.php?action=admin&page=sellers"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
            <li><a href="index.php?action=admin&page=reviews"><i data-lucide="star"></i><span>Reseñas</span></a></li>
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
            <h1>Hola, <?php echo htmlspecialchars($data['admin_nombre']); ?> 👋</h1>
        </header>

        <div class="dashboard-content">
            <section class="overview-cards">
                <div class="card">
                    <i data-lucide="eye"></i>
                    <div>
                        <h3>Total de visitas</h3>
                        <p>4.42.236 <span class="percentage positive">+59.3%</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="users-2"></i>
                    <div>
                        <h3>Total de usuarios</h3>
                        <p>
                            <?php echo number_format($data['stats']['total_usuarios']); ?>
                            <span class="percentage <?php echo ($data['stats']['cambio_porcentual_usuarios'] >= 0) ? 'positive' : 'neutral'; ?>">
                                <?php echo ($data['stats']['cambio_porcentual_usuarios'] >= 0 ? '+' : '') . number_format($data['stats']['cambio_porcentual_usuarios'], 1); ?>%
                            </span>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="shopping-cart"></i>
                    <div>
                        <h3>Pedido total</h3>
                        <p><?php echo number_format($data['stats']['total_pedidos']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ventas totales</h3>
                        <p>$<?php echo number_format($data['stats']['total_ventas']);?></p>
                    </div>
                </div>
            </section>
            
            <section class="grid-row top-row">
                <div class="card unique-visitor-card">
                    <div class="chart-header">
                        <h3>Visitante único</h3>
                        <div class="chart-controls">
                            <button class="active">Mes</button>
                            <button>Semana</button>
                        </div>
                    </div>
                    <div class="chart-canvas-container">
                        <canvas id="uniqueVisitorChart"></canvas>
                    </div>
                    <button class="descargar-reporte" onclick="descargarReporte('uniqueVisitorChart')">Descargar PDF</button>
                </div>
                <div class="card income-summary-card">
                    <h3>Resumen de Ingresos</h3>
                    <p class="this-week-stats">Estadísticas de esta semana</p>
                    <p class="income-amount">$7,650</p>
                    <div class="chart-canvas-container income-chart-container">
                        <canvas id="weeklyIncomeChart"></canvas>
                    </div>
                <button class="descargar-reporte" onclick="descargarReporte('weeklyIncomeChart')">Descargar PDF</button>
                </div>
            </section>

                        <section class="grid-row middle-row">
                <div class="card recent-orders-card">
                    <h3>Pedidos recientes</h3>
                    <table>
                        <thead><tr><th>FECHA</th><th>PRODUCTO</th><th>ESTADO</th><th>IMPORTE</th></tr></thead>
                        <tbody>
                            <?php if (!empty($data['recent_orders'])): ?>
                                <?php foreach ($data['recent_orders'] as $pedido): ?>
                                    <tr>
                                        <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                        <td><?php echo htmlspecialchars($pedido['nombre_producto']); ?></td>
                                        <td><span class="status <?php echo strtolower(htmlspecialchars($pedido['estado'])); ?>"><?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?></span></td>
                                        <td>$<?php echo number_format($pedido['importe'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No hay pedidos recientes.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                                 <div class="card analysis-report-card">
                    <h3>Informe de análisis</h3>
                    <div class="chart-canvas-container analysis-chart-container"><canvas id="companyFinanceChart"></canvas></div>
                    <button class="descargar-reporte" onclick="descargarReporte('companyFinanceChart')">Descargar PDF</button>
                </div>
            </section>
            
            <section class="grid-row bottom-row">
                <div class="card sales-report-card">
                    <div class="chart-header">
                        <h3>Informe de ventas</h3>
                        <div class="chart-controls text-style">
                             <button>Hoy</button>
                             <button>Semana</button>
                             <button>Mes</button>
                             <button class="active">Año</button>
                        </div>
                    </div>
                    <p class="net-benefit">Beneficio neto</p>
                    <p class="net-benefit-amount">$230,000</p>
                    <div class="chart-canvas-container">
                        <canvas id="salesReportChart"></canvas>
                    </div>
                    <button class="descargar-reporte" onclick="descargarReporte('salesReportChart')">Descargar PDF</button>
                </div>                
                <div class="card transaction-history-card">
                    <h3>Historial de transacciones</h3>
                    <div class="transaction-list">
                        <div class="transaction-item">
                            <div class="transaction-icon green-bg"><span>+</span></div>
                            <div class="transaction-details">
                                <p>Pedido #002434</p>
                                <span>Hoy, 2:00 AM</span>
                            </div>
                            <div class="transaction-amount">
                                <span class="amount positive">+ $1,430</span>
                            </div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-icon blue-bg"><span>+</span></div>
                            <div class="transaction-details">
                                <p>Pedido n.º 984947</p>
                                <span>5 de agosto, 13:45</span>
                            </div>
                            <div class="transaction-amount">
                                <span class="amount positive">+ $302</span>
                            </div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-icon red-bg"><span>+</span></div>
                            <div class="transaction-details">
                                <p>Pedido n.º 988784</p>
                                <span>Hace 7 horas</span>
                            </div>
                            <div class="transaction-amount">
                                <span class="amount positive">+ $682</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card chat-support-card">
                    <div class="chat-avatars">
                        <img src="https://i.pravatar.cc/35?img=1" alt="Avatar 1">
                        <img src="https://i.pravatar.cc/35?img=2" alt="Avatar 2">
                        <img src="https://i.pravatar.cc/35?img=3" alt="Avatar 3">
                    </div>
                    <p>Repetición típica en 5 minutos</p>
                    <button class="help-button">¿Necesitas ayuda?</button>
                </div>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../../public/js/alertas.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        lucide.createIcons();
    </script>
    <script src="js/admin/script-admin.js"></script>
</body>
</html>