<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/vendedor/productos.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <title>Dashboard - Pedidos</title>
</head>
<body>
      <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="img/logo.svg" alt="Logo" class="logo-img"/>
      </div>
      <ul class="menu">
           <li><a href="index.php?action=seller&page=orders"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li><a href="index.php?action=seller&page=productos"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li class="active"><a href="index.php?action=seller&page=orders"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="index.php?action=seller&page=subir-productos"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="index.php?action=seller&page=ingresos"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
      </ul>
      <div class="user-profile-container">
          <div class="user" id="userProfileBtn">
              <img src="https://i.pravatar.cc/40" alt="user" />
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
            <header class="header"><h1>Gestión de Pedidos</h1></header>

            <section class="cards">
                <div class="card">
                    <i data-lucide="calendar-clock"></i>
                    <div>
                        <h3>Pedidos de Hoy</h3>
                        <p><?php echo number_format($data['stats']['pedidos_hoy']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="loader"></i>
                    <div>
                        <h3>Pedidos Pendientes</h3>
                        <p><?php echo number_format($data['stats']['pedidos_pendientes']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="package-check"></i>
                    <div>
                        <h3>Pedidos Completados</h3>
                        <p><?php echo number_format($data['stats']['pedidos_completados']); ?></p>
                    </div>
                </div>
            </section>

            <section class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id_pedido']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($order['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['nombre_cliente']); ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower(htmlspecialchars($order['estado'])); ?>">
                                            <?php echo ucfirst(htmlspecialchars($order['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon edit-status-btn"><i data-lucide="edit"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No tienes pedidos para mostrar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
  <script>
    lucide.createIcons();
  </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/vendedor/pedidos.js"></script>
</body>
</html>