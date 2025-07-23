<?php
session_start();

// Guardián para la sección de Vendedor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../auth/login.php?status=sesion_expirada");
    exit;
}
$_SESSION['last_activity'] = time();

// --- CONEXIÓN Y OBTENCIÓN DEL ID DEL VENDEDOR ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

// Obtener el id_vendedor y nombre a partir del id_usuario en la sesión
$id_usuario_session = $_SESSION['id_usuario'];
$stmt_vendedor_info = $conexion->prepare("SELECT id_vendedor, nombre FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor_info->bind_param("i", $id_usuario_session);
$stmt_vendedor_info->execute();
$vendedor_info = $stmt_vendedor_info->get_result()->fetch_assoc();

if (!$vendedor_info) {
    die("Error: Perfil de vendedor no encontrado.");
}
$id_vendedor = $vendedor_info['id_vendedor'];
$nombre_vendedor = $vendedor_info['nombre'];
$stmt_vendedor_info->close();

// --- CONSULTAS PARA LAS TARJETAS (FILTRADAS POR VENDEDOR) ---

// 1. Ingresos Totales del Vendedor
$ingresos_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
                   FROM detalle_pedido dp
                   JOIN pedido p ON dp.id_pedido = p.id_pedido
                   WHERE p.id_vendedor = ?";
$stmt = $conexion->prepare($ingresos_query);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$ingresos_totales = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// 2. Pedidos Realizados al Vendedor
$pedidos_query = "SELECT COUNT(*) as total FROM pedido WHERE id_vendedor = ?";
$stmt = $conexion->prepare($pedidos_query);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$pedidos_realizados = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// 3. Clientes Únicos del Vendedor
$clientes_query = "SELECT COUNT(DISTINCT id_cliente) as total FROM pedido WHERE id_vendedor = ?";
$stmt = $conexion->prepare($clientes_query);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$nuevos_clientes = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


// 4. Consulta para la Tabla de "Pedidos Recientes"
$pedidos_recientes_query = "SELECT 
                                p.id_pedido, 
                                prod.nombre_producto, 
                                p.estado, 
                                (dp.cantidad * dp.precio_unitario) as importe
                            FROM pedido p
                            JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                            JOIN producto prod ON dp.id_producto = prod.id_producto
                            WHERE p.id_vendedor = ?
                            ORDER BY p.fecha DESC
                            LIMIT 5";
$stmt = $conexion->prepare($pedidos_recientes_query);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$resultado_pedidos_recientes = $stmt->get_result();

$conexion->close();
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
                <a href="../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
            </div>
        </div>
    </aside>

        <main class="main">
            <header class="header">
                <h1>Hola, <?php echo htmlspecialchars(explode(' ', $nombre_vendedor)[0]); ?> 👋</h1>
            </header>

            <div class="dashboard-content">
                <section class="overview-cards">
                    <div class="card">
                        <i data-lucide="dollar-sign"></i>
                        <div>
                            <h3>Ingresos Totales</h3>
                            <p>$<?php echo number_format($ingresos_totales, 2); ?></p>
                        </div>
                    </div>
                    <div class="card">
                        <i data-lucide="shopping-cart"></i>
                        <div>
                            <h3>Pedidos Realizados</h3>
                            <p><?php echo number_format($pedidos_realizados); ?></p>
                        </div>
                    </div>
                    <div class="card">
                        <i data-lucide="user-plus"></i>
                        <div>
                            <h3>Clientes Únicos</h3>
                            <p><?php echo number_format($nuevos_clientes); ?></p>
                        </div>
                    </div>
                    <div class="card">
                        <i data-lucide="activity"></i>
                        <div>
                            <h3>Tasa de Conversión</h3>
                            <p>N/A</p> </div>
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