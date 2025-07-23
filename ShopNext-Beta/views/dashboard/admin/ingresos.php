<?php
session_start();

// Guardián para la sección de Administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

// --- CONEXIÓN Y CONSULTAS DE INGRESOS ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

// 1. Ingresos Totales (Suma de todos los pedidos)
$ingresos_totales_query = "SELECT SUM(cantidad * precio_unitario) as total FROM detalle_pedido";
$ingresos_totales = $conexion->query($ingresos_totales_query)->fetch_assoc()['total'] ?? 0;

// 2. Ingresos del Mes Actual
$ingresos_mes_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total_mes
                       FROM detalle_pedido dp
                       JOIN pedido p ON dp.id_pedido = p.id_pedido
                       WHERE MONTH(p.fecha) = MONTH(CURDATE()) AND YEAR(p.fecha) = YEAR(CURDATE())";
$ingresos_mes = $conexion->query($ingresos_mes_query)->fetch_assoc()['total_mes'] ?? 0;

// 3. Ventas del Día de Hoy
$ventas_hoy_query = "SELECT COUNT(DISTINCT id_pedido) as ventas_hoy
                     FROM pedido
                     WHERE DATE(fecha) = CURDATE()";
$ventas_hoy = $conexion->query($ventas_hoy_query)->fetch_assoc()['ventas_hoy'] ?? 0;

// 4. Consulta para la tabla de Pedidos Recientes (¡CORREGIDA!)
$pedidos_recientes_query = "SELECT
                                p.id_pedido,
                                p.fecha,
                                c.nombre AS nombre_cliente, -- ¡¡LA CORRECCIÓN ESTABA AQUÍ!! Se busca en la tabla 'cliente' (c), no 'usuario' (u).
                                p.estado,
                                (SELECT SUM(dp.cantidad * dp.precio_unitario) 
                                 FROM detalle_pedido dp 
                                 WHERE dp.id_pedido = p.id_pedido) AS total_pedido
                            FROM pedido p
                            JOIN cliente c ON p.id_cliente = c.id_cliente
                            ORDER BY p.fecha DESC
                            LIMIT 20";
$resultado_pedidos = $conexion->query($pedidos_recientes_query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Ingresos</title>
  <link rel="stylesheet" href="../../../public/css/admin/ingresos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">  
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img">
      </div>
      <ul class="menu">
        <li><a href="../adminView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
        <li><a href="productos.php"><i data-lucide="box"></i><span>Productos</span></a></li>
        <li><a href="clientes.php"><i data-lucide="users"></i><span>Clientes</span></a></li>
        <li><a href="ingresos.php"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
        <li><a href="ayuda.php"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
        <li><a href="vendedores.php"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
      </ul>
      
      <div class="profile-area">
        <div class="user" id="userProfileBtn">
          <img src="https://i.pravatar.cc/40" alt="usuario" />
          <div class="user-info">
            <p>Brayan</p>
            <small>Administrador</small>
          </div>
          <i data-lucide="chevron-down" class="profile-arrow"></i>
        </div>
        <div id="profileDropdownMenu" class="profile-dropdown">
          <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i>Cerrar Sesión</a>
        </div>
      </div>
      </aside>

    <main class="main">
  <header class="header">
    <h1>Hola, Brayan 👋</h1>
  </header>

            <section class="cards" id="ingresos-cards">
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ingresos Totales</h3>
                        <p>$<?php echo number_format($ingresos_totales, 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="calendar"></i>
                    <div>
                        <h3>Ingresos del Mes</h3>
                        <p>$<?php echo number_format($ingresos_mes, 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="shopping-cart"></i>
                    <div>
                        <h3>Ventas del Día</h3>
                        <p><?php echo number_format($ventas_hoy); ?></p>
                    </div>
                </div>
            </section>

            <section class="table-section" id="pedidos-table">
                <div class="table-header">
                    <h2>Pedidos Recientes</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado del Pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado_pedidos && $resultado_pedidos->num_rows > 0) {
                            while ($pedido = $resultado_pedidos->fetch_assoc()) {
                                $estado_clase = strtolower(htmlspecialchars($pedido['estado']));
                        ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                    <td>$<?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                    <td><span class="status <?php echo $estado_clase; ?>"><?php echo ucfirst($estado_clase); ?></span></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No hay pedidos registrados.</td></tr>";
                        }
                        $conexion->close();
                        ?>
                    </tbody>
                </table>
            </section>
</main>

   <script>
    lucide.createIcons();
  </script>
  <script src="../../../public/js/admin/productos.js"></script>
    <script src="../../../public/js/main.js"></script>
</body>
</html>