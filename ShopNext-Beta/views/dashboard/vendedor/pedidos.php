<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../auth/login.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

$id_usuario_session = $_SESSION['id_usuario'];
$stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor->bind_param("i", $id_usuario_session);
$stmt_vendedor->execute();
$id_vendedor = $stmt_vendedor->get_result()->fetch_assoc()['id_vendedor'];
$stmt_vendedor->close();

$sql_pedidos = "SELECT 
                    p.id_pedido, p.fecha, c.nombre AS nombre_cliente, p.estado, 
                    (SELECT SUM(dp.cantidad * dp.precio_unitario) FROM detalle_pedido dp WHERE dp.id_pedido = p.id_pedido) AS total
                FROM pedido p
                JOIN cliente c ON p.id_cliente = c.id_cliente
                WHERE p.id_vendedor = ?
                ORDER BY p.fecha DESC";
$stmt = $conexion->prepare($sql_pedidos);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$resultado_pedidos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/vendedor/productos.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <title>Dashboard - Pedidos</title>
</head>
<body>
      <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img"/>
      </div>
      <ul class="menu">
          <li class="active"><a href="../vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
          <li><a href="productos.php"><i data-lucide="package"></i><span>Productos</span></a></li>
          <li><a href="pedidos.php"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
          <li><a href="subirProductos.php"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
          <li><a href="ingresos.php"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
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
              <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
              <a href="#configuracion"><i data-lucide="settings"></i><span>Configuración</span></a>
              <a href="#../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
          </div>
      </div>
    </aside>

    <main class="main">
      <header class="header" id="pedidos-header">
        <h1>Gestión de Pedidos</h1>
        <div class="header-search-container">
            <div class="input-icon header-search">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Buscar pedido por ID, cliente..." />
            </div>
        </div>
      </header>

      <section class="cards" id="pedidos-cards">
        <div class="card">
          <i data-lucide="calendar-clock"></i>
          <div>
            <h3>Pedidos de Hoy</h3>
            <p>42 <span class="success">+5</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="loader"></i>
          <div>
            <h3>Pedidos Pendientes</h3>
            <p>18</p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="package-check"></i>
          <div>
            <h3>Pedidos Completados</h3>
            <p>1,287</p>
          </div>
        </div>
      </section>

            <section class="table-section" id="pedidos-table">
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
                        <?php if ($resultado_pedidos && $resultado_pedidos->num_rows > 0): ?>
                            <?php while ($pedido = $resultado_pedidos->fetch_assoc()): ?>
                                <tr data-id="<?php echo $pedido['id_pedido']; ?>" data-estado="<?php echo strtolower($pedido['estado']); ?>">
                                    <td>#<?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower(htmlspecialchars($pedido['estado'])); ?>">
                                            <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon edit-status-btn" title="Cambiar Estado">
                                            <i data-lucide="edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;">No tienes pedidos todavía.</td></tr>
                        <?php endif; $conexion->close(); ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

  <script>
    lucide.createIcons();
  </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../../../public/js/vendedor/pedidos.js"></script>
</body>
</html>