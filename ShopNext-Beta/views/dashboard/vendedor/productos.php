<?php
session_start();

// Guardián para proteger la página
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../auth/login.php");
    exit;
}
// Actualiza la última actividad para evitar que la sesión expire
$_SESSION['last_activity'] = time();

// --- CONEXIÓN A LA BASE DE DATOS Y CONSULTAS ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { 
    die("Falló la conexión: " . $conexion->connect_error); 
}

$id_usuario_session = $_SESSION['id_usuario'];
$id_vendedor = null;
$nombre_vendedor = 'Vendedor'; // Nombre por defecto

// 1. Obtenemos el id_vendedor y el nombre a partir del id_usuario de la sesión
$stmt_vendedor = $conexion->prepare("SELECT id_vendedor, nombre FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor->bind_param("i", $id_usuario_session);
$stmt_vendedor->execute();
$resultado_vendedor = $stmt_vendedor->get_result();

if ($resultado_vendedor->num_rows > 0) {
    $vendedor = $resultado_vendedor->fetch_assoc();
    $id_vendedor = $vendedor['id_vendedor'];
    $nombre_vendedor = $vendedor['nombre']; // Guardamos el nombre para mostrarlo
}
$stmt_vendedor->close();

// Si no se encontró un vendedor, es un error y detenemos la ejecución
if ($id_vendedor === null) {
    die("Error de seguridad: No se encontró un perfil de vendedor asociado a este usuario.");
}

// 2. Consultas para las Tarjetas de Estadísticas (solo para este vendedor)
// Total de productos
$total_productos_query = $conexion->prepare("SELECT COUNT(*) as total FROM producto WHERE id_vendedor = ?");
$total_productos_query->bind_param("i", $id_vendedor);
$total_productos_query->execute();
$total_productos = $total_productos_query->get_result()->fetch_assoc()['total'];
$total_productos_query->close();

// Valor del inventario
$valor_inventario_query = $conexion->prepare("SELECT SUM(precio * stock) as valor_total FROM producto WHERE id_vendedor = ?");
$valor_inventario_query->bind_param("i", $id_vendedor);
$valor_inventario_query->execute();
$valor_inventario = $valor_inventario_query->get_result()->fetch_assoc()['valor_total'];
$valor_inventario_query->close();

// Productos agotados
$agotados_query = $conexion->prepare("SELECT COUNT(*) as agotados FROM producto WHERE id_vendedor = ? AND stock = 0");
$agotados_query->bind_param("i", $id_vendedor);
$agotados_query->execute();
$productos_agotados = $agotados_query->get_result()->fetch_assoc()['agotados'];
$agotados_query->close();

// 3. Consulta para la Tabla de Productos
$sql_productos = "SELECT id_producto, nombre_producto, precio, categoria, stock, ruta_imagen FROM producto WHERE id_vendedor = ?";
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $id_vendedor);
$stmt_productos->execute();
$resultado_productos = $stmt_productos->get_result();

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
    <title>Dashboard - Mis Productos</title>
</head>
<body>
      <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img"/>
      </div>
      <ul class="menu">
           <li><a href="../vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li class="active"><a href="productos.php"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li><a href="pedidos.php"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="subirProductos.php"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="ingresos.php"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
      </ul>
      <div class="user-profile-container">
          <div class="user" id="userProfileBtn">
              <img src="https://i.pravatar.cc/40" alt="user" />
              <div class="user-info">
                  <p><?php echo htmlspecialchars($nombre_vendedor); ?></p>
                  <small>Vendedor</small>
              </div>
              <i data-lucide="chevron-down" class="profile-arrow"></i>
          </div>
          <div class="profile-dropdown" id="profileDropdownMenu">
              <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
              <a href="#configuracion"><i data-lucide="settings"></i><span>Configuración</span></a>
              <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
          </div>
      </div>
    </aside>

    <main class="main">
      <header class="header" id="productos-header">
        <h1>Mis Productos</h1>
        </header>

      <section class="cards" id="productos-cards">
        <div class="card">
          <i data-lucide="package"></i>
          <div>
            <h3>Todos los Productos</h3>
            <p><?php echo number_format($total_productos); ?></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="dollar-sign"></i>
          <div>
            <h3>Valor del Inventario</h3>
            <p>$<?php echo number_format($valor_inventario ?? 0, 2); ?></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="package-x"></i>
          <div>
            <h3>Agotados</h3>
            <p><?php echo number_format($productos_agotados); ?></p>
          </div>
        </div>
      </section>

      <section class="table-section" id="pedidos-table">
        <div class="table-header">
          <h2>Tus Productos Publicados</h2>
          <a href="subirProductos.php" class="btn-add-product" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
              <i data-lucide="plus"></i> Añadir Nuevo Producto
          </a>
        </div>
        <table>
          <thead>
            <tr>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($resultado_productos && $resultado_productos->num_rows > 0) {
                while ($fila = $resultado_productos->fetch_assoc()) {
            ?>
                    <tr data-id="<?php echo htmlspecialchars($fila['id_producto']); ?>">
                        <td class="product-cell">
                            <img src="../../../public/uploads/products/<?php echo htmlspecialchars($fila['ruta_imagen'] ? $fila['ruta_imagen'] : 'default.png'); ?>" alt="<?php echo htmlspecialchars($fila['nombre_producto']); ?>" class="product-image">
                            <span><?php echo htmlspecialchars($fila['nombre_producto']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
                        <td>$<?php echo number_format($fila['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($fila['stock']); ?> unidades</td>
                        <td class="table-actions">
                            <div class="action-icons">
                                <a href="#" class="action-icon edit-btn" title="Editar"><i data-lucide="file-pen-line"></i></a>
                                <a href="#" class="action-icon delete-btn" title="Eliminar"><i data-lucide="trash-2"></i></a>
                            </div>
                        </td>
                    </tr>
            <?php
                } // Fin del while
            } else {
                echo "<tr><td colspan='5' class='text-center'>Aún no has subido ningún producto. ¡Añade uno!</td></tr>";
            }
            $stmt_productos->close();
            $conexion->close();
            ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>

  <script>lucide.createIcons();</script>
  <script src="../../../public/js/vendedor/productos.js"></script>
</body>
</html>