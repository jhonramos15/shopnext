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

// --- Consultas para las Tarjetas ---
$total_tickets = $conexion->query("SELECT COUNT(*) as total FROM tickets")->fetch_assoc()['total'] ?? 0;
$nuevos_hoy = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE DATE(fecha_creacion) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$tickets_abiertos = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Abierto'")->fetch_assoc()['total'] ?? 0;
$tickets_urgentes = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Abierto' AND prioridad = 'Alta'")->fetch_assoc()['total'] ?? 0;
$tickets_resueltos = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Resuelto'")->fetch_assoc()['total'] ?? 0;
$resueltos_hoy = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Resuelto' AND DATE(fecha_creacion) = CURDATE()")->fetch_assoc()['total'] ?? 0;

// --- NUEVA CONSULTA PARA LA TABLA DE PETICIONES ---
$peticiones_query = "SELECT 
                        t.id_ticket,
                        c.nombre AS nombre_cliente,
                        t.asunto,
                        t.fecha_creacion,
                        t.prioridad,
                        t.estado
                     FROM tickets t
                     JOIN usuario u ON t.id_usuario = u.id_usuario
                     JOIN cliente c ON u.id_usuario = c.id_usuario
                     ORDER BY t.fecha_creacion DESC
                     LIMIT 10"; // Limitamos a los 10 más recientes
$resultado_peticiones = $conexion->query($peticiones_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/admin/ayuda.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">    
    <title>Dashboard | Ayuda</title>
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
            <header class="header" id="ayuda-header">
                <h1>Hola, Brayan 👋</h1>
                <div class="header-search-container">
                    <div class="input-icon header-search">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Buscar ticket..." />
                    </div>
                </div>
            </header>

            <section class="cards" id="ayuda-cards">
                <div class="card">
                    <i data-lucide="ticket"></i>
                    <div>
                        <h3>Total Tickets</h3>
                        <p><?php echo number_format($total_tickets); ?> <span class="success"><?php echo $nuevos_hoy; ?> nuevos hoy</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="inbox"></i>
                    <div>
                        <h3>Tickets Abiertos</h3>
                        <p><?php echo number_format($tickets_abiertos); ?> <span class="danger"><?php echo $tickets_urgentes; ?> urgentes</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="check-circle-2"></i>
                    <div>
                        <h3>Tickets Resueltos</h3>
                        <p><?php echo number_format($tickets_resueltos); ?> <span class="success"><?php echo $resueltos_hoy; ?> hoy</span></p>
                    </div>
                </div>
            </section>

            <section class="table-section" id="ayuda-table">
                <div class="table-header">
                    <h2>Peticiones Recientes</h2>
                    <div class="right-controls">
                        </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado_peticiones && $resultado_peticiones->num_rows > 0): ?>
                            <?php while ($ticket = $resultado_peticiones->fetch_assoc()): ?>
                                <tr data-id="<?php echo htmlspecialchars($ticket['id_ticket']); ?>">
                                    <td><?php echo htmlspecialchars($ticket['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                                    <td><?php echo date("d M, Y", strtotime($ticket['fecha_creacion'])); ?></td>
                                    <td>
                                        <span class="status priority-<?php echo strtolower(htmlspecialchars($ticket['prioridad'])); ?>">
                                            <?php echo htmlspecialchars($ticket['prioridad']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status status-<?php echo strtolower(htmlspecialchars($ticket['estado'])); ?>">
                                            <?php echo htmlspecialchars($ticket['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon ver-btn" title="Ver Ticket"><i data-lucide="eye"></i></a>

                                        <a href="#" class="action-icon responder-btn" title="Marcar como Resuelto"><i data-lucide="send"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No hay tickets recientes.</td>
                            </tr>
                        <?php endif; ?>
                        <?php $conexion->close(); ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
  <script>
    lucide.createIcons();
  </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../public/js/admin/ayuda.js"></script> 
</body>
</html>