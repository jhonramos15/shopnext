<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 300;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactividad) {
    session_unset();
    session_destroy();
    header("Location: ../../auth/login.php?mensaje=sesion_expirada");
    exit;
}
$_SESSION['last_activity'] = time(); // Refresca el tiempo de actividad

// --- CONEXIÓN Y CONSULTAS ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}

// --- Consulta para la Tarjeta de "Vendedores Totales" ---
$total_vendedores_query = "SELECT COUNT(*) as total FROM usuario WHERE rol = 'vendedor'";
$total_vendedores_result = $conexion->query($total_vendedores_query);
$total_vendedores = $total_vendedores_result->fetch_assoc()['total'];

// --- Consulta para la Tabla (la que ya no dará error) ---
$sql_vendedores = "SELECT u.id_usuario, v.nombre, u.correo_usuario, u.estado
                   FROM usuario u
                   JOIN vendedor v ON u.id_usuario = v.id_usuario
                   WHERE u.rol = 'vendedor'";
$query_resultado = $conexion->query($sql_vendedores);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/admin/vendedores.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>Dashboard | Vendedores</title>
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
            <header class="header" id="vendedores-header">
                <h1>Hola, Brayan 👋</h1>
                </header>

            <section class="cards" id="vendedores-cards">
                <div class="card">
                    <i data-lucide="user-check"></i>
                    <div>
                        <h3>Vendedores Totales</h3>
                        <p><?php echo number_format($total_vendedores); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="trending-up"></i>
                    <div>
                        <h3>Ventas del Mes</h3>
                        <p>$12,540</p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="award"></i>
                    <div>
                        <h3>Mejor Vendedor</h3>
                        <p>Carlos Diaz</p>
                    </div>
                </div>
            </section>

            <section class="table-section" id="vendedores-table">
                <div class="table-header">
                    <h2>Todos los Vendedores</h2>
                    </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Vendedor</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // El bucle que rellena la tabla con datos reales
                        if ($query_resultado && $query_resultado->num_rows > 0) {
                            while ($fila = $query_resultado->fetch_assoc()) {
                        ?>
                                <tr data-id="<?php echo htmlspecialchars($fila['id_usuario']); ?>">
                                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['correo_usuario']); ?></td>
                                    <td>
                                        <div class="status-actions-container">
                                            <span class="status <?php echo ($fila['estado'] === 'activo') ? 'active' : 'inactive'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($fila['estado'])); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="table-actions">
                                        <div class="action-icons">
                                            <a href="#" class="action-icon edit-btn" title="Editar"><i data-lucide="edit-2"></i></a>
                                            <a href="#" class="action-icon delete-btn" title="Eliminar"><i data-lucide="trash-2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            } // Fin del while
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No se encontraron vendedores registrados.</td></tr>";
                        }
                        $conexion->close(); // Cerramos la conexión aquí
                        ?>
                    </tbody>
                </table>
                </section>
        </main>
    </div>

    <script>lucide.createIcons();</script>
    <script src="../../../public/js/admin/vendedores.js"></script>
</body>
</html>