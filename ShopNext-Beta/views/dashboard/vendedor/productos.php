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
    <title>Dashboard - Pedidos en Curso</title>
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
              <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
          </div>
      </div>
    </aside>

    <main class="main">
      <header class="header" id="productos-header">
        <h1>Hola, Brayan 👋</h1>
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
            <p>1,250 <span class="success">120+ nuevos</span></p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="dollar-sign"></i>
          <div>
            <h3>Valor del Inventario</h3>
            <p>$250,430.00</p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="package-x"></i>
          <div>
            <h3>Agotados</h3>
            <p>32 <span class="danger">5 más que ayer</span></p>
          </div>
        </div>
      </section>

      <section class="table-section" id="pedidos-table">
        <div class="table-header">
          <h2>Pedidos en Curso</h2>
          <div class="right-controls">
            <div class="input-icon table-search">
              <i data-lucide="search"></i>
              <input type="text" placeholder="Buscar pedido..." />
            </div>
            <div class="custom-select table-select">
              <select>
                <option selected>Estado: Todos</option>
                <option>Estado: Activo</option>
                <option>Estado: Terminado</option>
                <option>Estado: Atrasado</option>
              </select>
            </div>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>PRODUCTO</th>
              <th>VENDEDOR</th>
              <th>PRECIO</th>
              <th>FECHA</th>
              <th>ESTADO Y ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>22</td>
              <td>Samsung Galaxy A24 Ultra</td>
              <td>José Ignacio López Gómez</td>
              <td>$3,365,900</td>
              <td>22/12/2023</td>
              <td>
                <div class="status-actions-container">
                  <span class="status active">Activo</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Editar"><i data-lucide="file-pen-line"></i></a>
                    <a href="#" class="action-icon" title="Configurar"><i data-lucide="settings"></i></a>
                    <a href="#" class="action-icon" title="Eliminar"><i data-lucide="trash-2"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>43</td>
              <td>Portátil HP Intel Celeron 8GB</td>
              <td>Juan David Rincón</td>
              <td>$1,340,000</td>
              <td>14/10/2024</td>
              <td>
                <div class="status-actions-container">
                  <span class="status active">Activo</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Editar"><i data-lucide="file-pen-line"></i></a>
                    <a href="#" class="action-icon" title="Configurar"><i data-lucide="settings"></i></a>
                    <a href="#" class="action-icon" title="Eliminar"><i data-lucide="trash-2"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>84</td>
              <td>Asador Premium Incanto XXL Deluxe</td>
              <td>Dana Sofía Vergara Quintero</td>
              <td>$965,900</td>
              <td>15/05/2020</td>
              <td>
                <div class="status-actions-container">
                  <span class="status inactive">Terminado</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Editar"><i data-lucide="file-pen-line"></i></a>
                    <a href="#" class="action-icon" title="Configurar"><i data-lucide="settings"></i></a>
                    <a href="#" class="action-icon" title="Eliminar"><i data-lucide="trash-2"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>12</td>
              <td>Pista Hot Wheels Año 2021</td>
              <td>Sandra Milena Cuellar Lozano</td>
              <td>$234,400</td>
              <td>04/10/2024</td>
              <td>
                <div class="status-actions-container">
                  <span class="status delayed">Atrasado</span>
                  <div class="action-icons">
                    <a href="#" class="action-icon" title="Editar"><i data-lucide="file-pen-line"></i></a>
                    <a href="#" class="action-icon" title="Configurar"><i data-lucide="settings"></i></a>
                    <a href="#" class="action-icon" title="Eliminar"><i data-lucide="trash-2"></i></a>
                  </div>
                </div>
              </td>
            </tr>
            </tbody>
        </table>
        <div class="pagination-controls">
            <div class="data-count">
                Mostrando <span>1</span> a <span>4</span> de <span>11</span> pedidos
            </div>
            <div class="pagination">
                <button class="pagination-button" disabled><i data-lucide="chevron-left"></i> <span>Anterior</span></button>
                <button class="pagination-button page-number active">1</button>
                <button class="pagination-button page-number">2</button>
                <button class="pagination-button page-number">3</button>
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