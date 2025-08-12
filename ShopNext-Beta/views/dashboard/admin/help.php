<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin/ayuda.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">    
    <title>Dashboard | Ayuda</title>
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
              <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
          </div>
      </div>
    </aside>

            <main class="main">
            <header class="header" id="ayuda-header">
                <h1>Hola, Brayan 👋</h1>
            </header>

            <section class="cards" id="ayuda-cards">
                <div class="card">
                    <i data-lucide="ticket"></i>
                    <div>
                        <h3>Total Tickets</h3>
                                                <p><?php echo number_format($data['stats']['total_tickets']); ?> <span class="success"><?php echo $data['stats']['nuevos_hoy']; ?> nuevos hoy</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="inbox"></i>
                    <div>
                        <h3>Tickets Abiertos</h3>
                        <p><?php echo number_format($data['stats']['tickets_abiertos']); ?> <span class="danger"><?php echo $data['stats']['tickets_urgentes']; ?> urgentes</span></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="check-circle-2"></i>
                    <div>
                        <h3>Tickets Resueltos</h3>
                        <p><?php echo number_format($data['stats']['tickets_resueltos']); ?> <span class="success"><?php echo $data['stats']['resueltos_hoy']; ?> hoy</span></p>
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
                        <?php if (!empty($data['tickets'])): ?>
                            <?php foreach ($data['tickets'] as $ticket): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ticket['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                                    <td><?php echo date("d M, Y", strtotime($ticket['fecha_creacion'])); ?></td>
                                    <td><span class="status priority-<?php echo strtolower(htmlspecialchars($ticket['prioridad'])); ?>"><?php echo htmlspecialchars($ticket['prioridad']); ?></span></td>
                                    <td><span class="status status-<?php echo strtolower(htmlspecialchars($ticket['estado'])); ?>"><?php echo htmlspecialchars($ticket['estado']); ?></span></td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon ver-btn"><i data-lucide="eye"></i></a>
                                        <a href="#" class="action-icon responder-btn"><i data-lucide="send"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No hay tickets recientes.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>lucide.createIcons();</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../public/js/admin/ayuda.js"></script> 
</body>
</html>