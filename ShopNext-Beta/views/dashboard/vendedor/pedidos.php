<?php
// views/dashboard/vendedor/pedidos.php
session_start();
// Guardián de seguridad (similar al de otros archivos de vendedor)

$conexion = new mysqli("localhost", "root", "", "shopnexs");
$id_usuario_session = $_SESSION['id_usuario'];

// Obtener el id_vendedor del usuario actual
$stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor->bind_param("i", $id_usuario_session);
$stmt_vendedor->execute();
$id_vendedor = $stmt_vendedor->get_result()->fetch_assoc()['id_vendedor'];

$sql_pedidos_vendedor = "SELECT p.id_pedido, p.fecha, p.estado, c.nombre as nombre_cliente, SUM(dp.cantidad * dp.precio_unitario) as total
                         FROM pedido p
                         JOIN cliente c ON p.id_cliente = c.id_cliente
                         JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                         WHERE p.id_vendedor = ?
                         GROUP BY p.id_pedido
                         ORDER BY p.fecha DESC";

$stmt = $conexion->prepare($sql_pedidos_vendedor);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
        <div class="table-header">
          <h2>Historial de Pedidos</h2>
          <div class="right-controls">
            <div class="input-icon table-search">
              <i data-lucide="search"></i>
              <input type="text" placeholder="Buscar..." />
            </div>
            <div class="custom-select table-select">
              <select>
                <option selected>Estado: Todos</option>
                <option>Enviado</option>
                <option>Entregado</option>
                <option>Pendiente</option>
                <option>Cancelado</option>
              </select>
            </div>
          </div>
        </div>
<table>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo $pedido['id_pedido']; ?></td>
                <td><?php echo $pedido['nombre_cliente']; ?></td>
                <td>$<?php echo number_format($pedido['total']); ?></td>
                <td>
                    <form action="/shopnext/ShopNext-Beta/controllers/vendedor/actualizarPedido.php" method="POST">
                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                        <select name="estado">
                            <option value="pendiente" <?php echo $pedido['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="en curso" <?php echo $pedido['estado'] == 'en curso' ? 'selected' : ''; ?>>En Curso</option>
                            <option value="finalizado" <?php echo $pedido['estado'] == 'finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                            <option value="cancelado" <?php echo $pedido['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                        <button type="submit">Actualizar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        <div class="pagination-controls">
            <div class="data-count">
                Mostrando <span>1</span> a <span>5</span> de <span>1,287</span> pedidos
            </div>
            <div class="pagination">
                <button class="pagination-button" disabled><i data-lucide="chevron-left"></i> <span>Anterior</span></button>
                <button class="pagination-button page-number active">1</button>
                <button class="pagination-button page-number">2</button>
                <button class="pagination-button page-number">3</button>
                <span class="pagination-ellipsis">...</span>
                <button class="pagination-button page-number">258</button>
                <button class="pagination-button"><span>Siguiente</span> <i data-lucide="chevron-right"></i></button>
            </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    lucide.createIcons();
  </script>
  <script src="../../../public/js/vendedor/productos.js"></script>
</body>
</html>