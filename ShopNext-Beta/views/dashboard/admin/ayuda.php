<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 300;

// Verificar si existe el tiempo de última actividad
if (isset($_SESSION['last_activity'])) {
    $tiempo_inactivo = time() - $_SESSION['last_activity'];

    if ($tiempo_inactivo > $inactividad) {
        // Cierra la sesión si pasó el tiempo
        session_unset();
        session_destroy();
        header("Location: ../../auth/login.php?mensaje=sesion_expirada");
        exit;
    } else {
        $_SESSION['last_activity'] = time(); // ✅ Refresca el tiempo de actividad
    }
} else {
    $_SESSION['last_activity'] = time(); // ✅ Inicializa el tiempo de actividad si no existía
}
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
            <p>452 <span class="success">25 nuevos hoy</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="inbox"></i>
          <div>
            <h3>Tickets Abiertos</h3>
            <p>75 <span class="danger">10 urgentes</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="check-circle-2"></i>
          <div>
            <h3>Tickets Resueltos</h3>
            <p>377 <span class="success">8 hoy</span></p>
          </div>
        </div>
      </section>

      <section class="table-section" id="ayuda-table">
        <div class="table-header">
          <h2>Peticiones Recientes</h2>
          <div class="right-controls">
            <div class="input-icon table-search">
              <i data-lucide="search"></i>
              <input type="text" placeholder="Buscar por asunto o usuario..." />
            </div>
            <div class="custom-select table-select">
              <select>
                <option selected>Prioridad: Todas</option>
                <option>Prioridad: Alta</option>
                <option>Prioridad: Media</option>
                <option>Prioridad: Baja</option>
              </select>
            </div>
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
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jane Cooper</td>
              <td>No puedo resetear mi contraseña</td>
              <td>18 Jun, 2025</td>
              <td><span class="status priority-alta">Alta</span></td>
              <td>
                <div class="status-actions-container">
                  <span class="status status-abierto">Abierto</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Ticket"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Responder"><i data-lucide="send"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Marvin McKinney</td>
              <td>Consulta sobre facturación</td>
              <td>17 Jun, 2025</td>
              <td><span class="status priority-media">Media</span></td>
              <td>
                <div class="status-actions-container">
                  <span class="status status-abierto">Abierto</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Ticket"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Responder"><i data-lucide="send"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Jerome Bell</td>
              <td>Sugerencia para nueva función</td>
              <td>17 Jun, 2025</td>
              <td><span class="status priority-baja">Baja</span></td>
              <td>
                <div class="status-actions-container">
                  <span class="status status-resuelto">Resuelto</span>
                   <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Ticket"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Reabrir"><i data-lucide="rotate-cw"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Ronald Richards</td>
              <td>Error al cargar mis archivos</td>
              <td>16 Jun, 2025</td>
              <td><span class="status priority-alta">Alta</span></td>
              <td>
                <div class="status-actions-container">
                  <span class="status status-resuelto">Resuelto</span>
                   <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Ticket"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Reabrir"><i data-lucide="rotate-cw"></i></a>
                  </div>
                </div>
              </td>
            </tr>
             <tr>
              <td>Kathryn Murphy</td>
              <td>¿Cómo exporto mis datos?</td>
              <td>15 Jun, 2025</td>
              <td><span class="status priority-media">Media</span></td>
              <td>
                <div class="status-actions-container">
                  <span class="status status-resuelto">Resuelto</span>
                   <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Ticket"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Reabrir"><i data-lucide="rotate-cw"></i></a>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="pagination-controls">
            <div class="data-count">
                Mostrando <span>1</span> a <span>5</span> de <span>75</span> tickets abiertos
            </div>
            <div class="pagination">
                <button class="pagination-button" disabled><i data-lucide="chevron-left"></i> <span>Anterior</span></button>
                <button class="pagination-button page-number active">1</button>
                <button class="pagination-button page-number">2</button>
                <button class="pagination-button page-number">3</button>
                <span class="pagination-ellipsis">...</span>
                <button class="pagination-button page-number">15</button>
                <button class="pagination-button"><span>Siguiente</span> <i data-lucide="chevron-right"></i></button>
            </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    lucide.createIcons();
  </script>
  <script src="../../../public/js/admin/productos.js"></script>
</body>
</html>