<?php
session_start();

// Guardián para la sección de Vendedor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../auth/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

// --- CONEXIÓN Y OBTENCIÓN DEL ID DEL VENDEDOR ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

$id_usuario_session = $_SESSION['id_usuario'];
$stmt_vendedor_id = $conexion->prepare("SELECT id_vendedor, nombre FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor_id->bind_param("i", $id_usuario_session);
$stmt_vendedor_id->execute();
$vendedor_info = $stmt_vendedor_id->get_result()->fetch_assoc();
$id_vendedor = $vendedor_info['id_vendedor'];
$nombre_vendedor = $vendedor_info['nombre'];
$stmt_vendedor_id->close();


// --- CONSULTAS DE INGRESOS FILTRADAS POR VENDEDOR (CORREGIDAS) ---

// 1. Ingresos de los últimos 30 días
$ingresos_30d_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
                       FROM detalle_pedido dp
                       JOIN pedido p ON dp.id_pedido = p.id_pedido
                       WHERE p.id_vendedor = ? AND p.fecha >= CURDATE() - INTERVAL 30 DAY";
$stmt30 = $conexion->prepare($ingresos_30d_query);
$stmt30->bind_param("i", $id_vendedor);
$stmt30->execute();
$ingresos_30_dias = $stmt30->get_result()->fetch_assoc()['total'] ?? 0;
$stmt30->close();

// 2. Ingresos de los últimos 7 días
$ingresos_7d_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
                      FROM detalle_pedido dp
                      JOIN pedido p ON dp.id_pedido = p.id_pedido
                      WHERE p.id_vendedor = ? AND p.fecha >= CURDATE() - INTERVAL 7 DAY";
$stmt7 = $conexion->prepare($ingresos_7d_query);
$stmt7->bind_param("i", $id_vendedor);
$stmt7->execute();
$ingresos_7_dias = $stmt7->get_result()->fetch_assoc()['total'] ?? 0;
$stmt7->close();

// 3. Consulta para la tabla de "Últimas Ventas"
$ultimas_ventas_query = "SELECT
                            c.nombre AS nombre_cliente,
                            prod.nombre_producto,
                            dp.precio_unitario,
                            u.correo_usuario AS email_cliente,
                            p.estado
                        FROM detalle_pedido dp
                        JOIN pedido p ON dp.id_pedido = p.id_pedido
                        JOIN producto prod ON dp.id_producto = prod.id_producto
                        JOIN cliente c ON p.id_cliente = c.id_cliente
                        JOIN usuario u ON c.id_usuario = u.id_usuario
                        WHERE p.id_vendedor = ?
                        ORDER BY p.fecha DESC, p.id_pedido DESC
                        LIMIT 8"; // Mostrar los últimos 8 artículos vendidos
$stmt_ventas = $conexion->prepare($ultimas_ventas_query);
$stmt_ventas->bind_param("i", $id_vendedor);
$stmt_ventas->execute();
$resultado_ventas = $stmt_ventas->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ingresos</title>
  <link rel="stylesheet" href="../../../public/css/vendedor/ingresos.css" />
  <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
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
          <a href="#mi-perfil"><i data-lucide="user"></i>Mi Perfil</a> 
          <a href="#configuracion"><i data-lucide="settings"></i>Configuración</a>
          <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i>Cerrar Sesión</a>
        </div>
      </div>
      </aside>

    <main class="main">
      <header class="header">
        <h1>Hola, <?php echo htmlspecialchars(explode(' ', $nombre_vendedor)[0]); ?> 👋</h1>
        </header>

      <section class="cards ingresos" id="ingresos-cards">
        <div class="card">
          <i data-lucide="hand-coins"></i>
          <div>
            <h3>Ingresos 30 días</h3>
            <p>$<?php echo number_format($ingresos_30_dias, 2); ?></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="dollar-sign"></i>
          <div>
            <h3>Ingresos 7 días</h3>
            <p>$<?php echo number_format($ingresos_7_dias, 2); ?></p>
          </div>
        </div>
      </section>

      <section class="table-section">
        <div class="table-header">
          <h2>Últimas Ventas</h2>
        </div>
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
            <?php if ($resultado_ventas && $resultado_ventas->num_rows > 0): ?>
              <?php while ($venta = $resultado_ventas->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($venta['nombre_cliente']); ?></td>
                  <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                  <td>$<?php echo number_format($venta['precio_unitario'], 2); ?></td>
                  <td><?php echo htmlspecialchars($venta['email_cliente']); ?></td>
                  <td><span class="badge <?php echo strtolower(htmlspecialchars($venta['estado'])); ?>"><?php echo htmlspecialchars($venta['estado']); ?></span></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align: center;">Aún no has registrado ninguna venta.</td></tr>
            <?php endif; $conexion->close(); ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>

   <script>
    lucide.createIcons();
  </script>
  <script src="../../../public/js/vendedor/ingresos.js"></script>
</body>
</html>