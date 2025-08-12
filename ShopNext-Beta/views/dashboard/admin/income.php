<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Ingresos</title>
  <link rel="stylesheet" href="css/admin/ingresos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">  
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="img/logo.svg" alt="Logo" class="logo-img">
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
    <header class="header"><h1>Hola, <?php echo htmlspecialchars($data['admin_nombre']); ?> 👋</h1></header>
  </header>

            <section class="cards" id="ingresos-cards">
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ingresos Totales</h3>
                        <p>$<?php echo number_format($data['stats']['ingresos_totales'], 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="calendar"></i>
                    <div>
                        <h3>Ingresos del Mes</h3>
                        <p>$<?php echo number_format($data['stats']['ingresos_mes'], 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="shopping-cart"></i>
                    <div>
                        <h3>Ventas del Día</h3>
                        <p><?php echo number_format($data['stats']['ventas_hoy']); ?></p>
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
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id_pedido']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($order['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['nombre_cliente']); ?></td>
                                    <td>$<?php echo number_format($order['total_pedido'], 2); ?></td>
                                    <td><span class="status <?php echo strtolower(htmlspecialchars($order['estado'])); ?>"><?php echo ucfirst(htmlspecialchars($order['estado'])); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No hay pedidos registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>lucide.createIcons();</script>
  <script src="../../../public/js/admin/ingresos.js"></script>
    <script src="../../../public/js/main.js"></script>
</body>
</html>