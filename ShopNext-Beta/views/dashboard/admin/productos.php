<?php
session_start();

// Guardián para la sección de Administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

// --- CONEXIÓN Y CONSULTAS ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

$total_productos = $conexion->query("SELECT COUNT(*) as total FROM producto")->fetch_assoc()['total'];
$valor_inventario = $conexion->query("SELECT SUM(precio * stock) as valor_total FROM producto")->fetch_assoc()['valor_total'];
$productos_agotados = $conexion->query("SELECT COUNT(*) as agotados FROM producto WHERE stock = 0")->fetch_assoc()['agotados'];

$sql_productos = "SELECT 
                    p.id_producto, p.nombre_producto, p.precio, p.categoria, p.stock,
                    v.nombre AS nombre_vendedor
                  FROM producto p
                  JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                  ORDER BY p.id_producto DESC";
$resultado_productos = $conexion->query($sql_productos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Productos</title>
    <link rel="stylesheet" href="../../../public/css/admin/productos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img">
            </div>
            <ul class="menu">
                <li><a href="../adminView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
                <li class="active"><a href="productos.php"><i data-lucide="box"></i><span>Productos</span></a></li>
                <li><a href="clientes.php"><i data-lucide="users"></i><span>Clientes</span></a></li>
                <li><a href="ingresos.php"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
                <li><a href="ayuda.php"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
                <li><a href="vendedores.php"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
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
                    <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
                </div>
            </div>
        </aside>

        <main class="main">
            <header class="header">
                <h1>Todos los Productos</h1>
                 <div class="header-search-container">
                    <div class="input-icon header-search">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Buscar..." />
                    </div>
                </div>
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

            <section class="table-section" id="productos-table">
                <div class="table-header">
                    <h2>Productos en la Tienda</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Producto</th>
                            <th>Vendedor</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado_productos && $resultado_productos->num_rows > 0) {
                            while ($fila = $resultado_productos->fetch_assoc()) {
                                $estado_texto = ($fila['stock'] > 0) ? 'Publicado' : 'Agotado';
                                $estado_clase = ($fila['stock'] > 0) ? 'active' : 'inactive';
                        ?>
                                <tr data-id="<?php echo htmlspecialchars($fila['id_producto']); ?>">
                                    <td><?php echo htmlspecialchars($fila['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['nombre_vendedor']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['stock']); ?></td>
                                    <td>$<?php echo number_format($fila['precio'], 2); ?></td>
                                    <td><span class="status <?php echo $estado_clase; ?>"><?php echo $estado_texto; ?></span></td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon" title="Ver"><i data-lucide="eye"></i></a>
                                        <a href="#" class="action-icon" title="Editar"><i data-lucide="edit-2"></i></a>
                                        <a href="#" class="action-icon delete-btn" title="Eliminar"><i data-lucide="trash-2"></i></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No hay productos registrados.</td></tr>";
                        }
                        $conexion->close();
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>lucide.createIcons();</script>
    <script src="../../../public/js/admin/main.js"></script>
    <script src="../../../public/js/admin/productos.js"></script>
</body>
</html>