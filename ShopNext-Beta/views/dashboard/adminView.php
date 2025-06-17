<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../auth/login.html");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 100;

// Verificar si existe el tiempo de última actividad
if (isset($_SESSION['last_activity'])) {
    $tiempo_inactivo = time() - $_SESSION['last_activity'];

    if ($tiempo_inactivo > $inactividad) {
        // Cierra la sesión si pasó el tiempo
        session_unset();
        session_destroy();
        header("Location: ../auth/login.php?mensaje=sesion_expirada");
        exit;
    }
}

// Actualiza el tiempo de última actividad
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/dashboardAdmin.css">
    <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
    <title>Dashboard Admin | ShopNext</title>
</head>
<body>
    <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container"> <img src="../../public/img/logo.svg" alt="Logo" class="logo-img"/>
        </div>
      <ul class="menu">
        <li class="active"><a href="#dashboard"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
        <li><a href="#productos"><i data-lucide="box"></i><span>Productos</span></a></li>
        <li><a href="#clientes"><i data-lucide="users"></i><span>Clientes</span></a></li>
        <li><a href="#ingresos"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
        <li><a href="#ayuda"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
        <li><a href="#vendedores"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
      </ul>
      <div class="user-profile-container">
          <div class="user" id="userProfileBtn">
              <img src="https://i.pravatar.cc/40" alt="user" />
              <div class="user-info"> <p>Brayan</p>
                  <small>Administrador</small>
              </div>
              <i data-lucide="chevron-down" class="profile-arrow"></i>
          </div>
          <div class="profile-dropdown" id="profileDropdownMenu">
              <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
              <a href="#configuracion"><i data-lucide="settings"></i><span>Configuración</span></a>
              <a href="../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>

          </div>
      </div>
    </aside>

    <main class="main">
      <header class="header" id="dashboard-header">
        <h1>Hola, Brayan 👋</h1>
        <div class="header-search-container"> <div class="input-icon header-search"> <i data-lucide="search"></i>
                <input type="text" placeholder="Buscar..." />
            </div>
        </div>
      </header>

      <section class="cards" id="productos-cards">
        <div class="card">
          <i data-lucide="users"></i>
          <div>
            <h3>Clientes Totales</h3>
            <p>5,423 <span class="success">18% este mes</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="user-x"></i>
          <div>
            <h3>Miembros</h3>
            <p>1,893 <span class="danger">1% este mes</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="monitor"></i>
          <div>
            <h3>Activos Ahora</h3>
            <p>189</p>
          </div>
        </div>
      </section>

      <section class="table-section" id="clientes-table">
        <div class="table-header">
          <h2>Todos los Clientes</h2>
          <div class="right-controls">
            <div class="input-icon table-search">
              <i data-lucide="search"></i>
              <input type="text" placeholder="Buscar cliente..." />
            </div>
            <div class="custom-select table-select">
              <select>
                <option selected>Ordenar: Nuevo</option>
                <option>Ordenar: Activo</option>
                <option>Ordenar: Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Nombre Cliente</th>
              <th>Ciudad</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jane Cooper</td>
              <td>Microsoft</td>
              <td>(225) 555-0118</td>
              <td>jane@microsoft.com</td>
              <td><span class="status active">Activo</span></td>
            </tr>
            <tr>
              <td>Floyd Miles</td>
              <td>Yahoo</td>
              <td>(205) 555-0100</td>
              <td>floyd@yahoo.com</td>
              <td><span class="status inactive">Inactivo</span></td>
            </tr>
            <tr>
              <td>Ronald Richards</td>
              <td>Adobe</td>
              <td>(302) 555-0107</td>
              <td>ronald@adobe.com</td>
              <td><span class="status inactive">Inactivo</span></td>
            </tr>
            <tr>
              <td>Marvin McKinney</td>
              <td>Tesla</td>
              <td>(252) 555-0126</td>
              <td>marvin@tesla.com</td>
              <td><span class="status active">Activo</span></td>
            </tr>
            <tr>
                <td>Jerome Bell</td>
                <td>Google</td>
                <td>(629) 555-0129</td>
                <td>jerome@google.com</td>
                <td><span class="status active">Activo</span></td>
            </tr>
            <tr>
                <td>Kathryn Murphy</td>
                <td>Microsoft</td>
                <td>(406) 555-0120</td>
                <td>kathryn@microsoft.com</td>
                <td><span class="status active">Activo</span></td>
            </tr>
          </tbody>
        </table>
        <div class="pagination">
            <button class="pagination-button" disabled><i data-lucide="chevron-left"></i> <span>Anterior</span></button>
            <button class="pagination-button page-number active">1</button>
            <button class="pagination-button page-number">2</button>
            <button class="pagination-button page-number">3</button>
            <span class="pagination-ellipsis">...</span>
            <button class="pagination-button page-number">8</button>
            <button class="pagination-button"><span>Siguiente</span> <i data-lucide="chevron-right"></i></button>
        </div>
      </section>
    </main>
  </div>

  <script>
    lucide.createIcons();
  </script>
  <script src="../../public/js/scriptAdmin.js"></script>
</body>
</html>