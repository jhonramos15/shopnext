<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ingresos</title>
  <link rel="stylesheet" href="css/vendedor/ingresos.css" />
  <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="img/logo.svg" alt="Logo" class="logo-img"/>
      </div>
      <ul class="menu">
          <li><a href="../vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li><a href="index.php?action=seller&page=productos"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li><a href="index.php?action=seller&page=orders"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="index.php?action=seller&page=subir-productos"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li class="active"><a href="index.php?action=seller&page=income"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
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
                <h1>Hola, <?php echo htmlspecialchars(explode(' ', $data['nombre_vendedor'])[0]); ?> 👋</h1>
            </header>

            <section class="cards ingresos">
                <div class="card">
                    <i data-lucide="hand-coins"></i>
                    <div>
                        <h3>Ingresos 30 días</h3>
                        <p>$<?php echo number_format($data['stats']['ingresos_30_dias'], 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Ingresos 7 días</h3>
                        <p>$<?php echo number_format($data['stats']['ingresos_7_dias'], 2); ?></p>
                    </div>
                </div>
            </section>

            <section class="table-section">
                <div class="table-header"><h2>Últimas Ventas</h2></div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Cliente</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Email</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['latest_sales'])): ?>
                            <?php foreach ($data['latest_sales'] as $sale): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sale['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($sale['nombre_producto']); ?></td>
                                    <td>$<?php echo number_format($sale['precio_unitario'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($sale['email_cliente']); ?></td>
                                    <td><span class="badge <?php echo strtolower(htmlspecialchars($sale['estado'])); ?>"><?php echo htmlspecialchars($sale['estado']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">Aún no has registrado ninguna venta.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
  </div>

   <script>
    lucide.createIcons();
  </script>
  <script src="js/vendedor/ingresos.js"></script>
</body>
</html>