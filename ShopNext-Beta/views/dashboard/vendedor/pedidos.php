<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../auth/login.html");
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
        header("Location: ../auth/login.php?mensaje=sesion_expirada");
        exit;
    } else {
        $_SESSION['last_activity'] = time(); // ✅ Refresca el tiempo de actividad
    }
} else {
    $_SESSION['last_activity'] = time(); // ✅ Inicializa el tiempo de actividad si no existía
}
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
          <thead>
            <tr>
              <th>ID Pedido</th>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Total</th>
              <th>Estado del Pago</th>
              <th>Estado del Envío</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#8A342</td>
              <td>Carlos Ramírez</td>
              <td>2025-06-30</td>
              <td>$1,289.99</td>
              <td><span class="status paid">Pagado</span></td>
              <td><span class="status delivered">Entregado</span></td>
              <td>
                <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                </div>
              </td>
            </tr>
            <tr>
              <td>#7B9F1</td>
              <td>Ana Sofía Rojas</td>
              <td>2025-06-29</td>
              <td>$89.50</td>
              <td><span class="status paid">Pagado</span></td>
              <td><span class="status shipped">Enviado</span></td>
               <td>
                <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                </div>
              </td>
            </tr>
            <tr>
              <td>#6C5D8</td>
              <td>Luisa Fernanda M.</td>
              <td>2025-06-29</td>
              <td>$275.00</td>
              <td><span class="status pending">Pendiente</span></td>
              <td><span class="status processing">En preparación</span></td>
               <td>
                <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                </div>
              </td>
            </tr>
            <tr>
              <td>#5E4A2</td>
              <td>Jorge Mendoza</td>
              <td>2025-06-28</td>
              <td>$45.00</td>
              <td><span class="status paid">Pagado</span></td>
              <td><span class="status delivered">Entregado</span></td>
               <td>
                <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                </div>
              </td>
            </tr>
             <tr>
              <td>#4D1B7</td>
              <td>Mariana Paredes</td>
              <td>2025-06-27</td>
              <td>$550.00</td>
              <td><span class="status paid">Pagado</span></td>
              <td><span class="status cancelled">Cancelado</span></td>
               <td>
                <div class="action-icons">
                    <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                </div>
              </td>
            </tr>
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