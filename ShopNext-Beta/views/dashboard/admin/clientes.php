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

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener los datos de clientes
$sql = "SELECT c.id_cliente, c.nombre, c.direccion, u.correo_usuario AS correo, u.estado 
        FROM cliente c
        INNER JOIN usuario u ON c.id_usuario = u.id_usuario";

$resultado = $conexion->query($sql);

// Verifica si la consulta falló
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/admin/clientes.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">    
    <title>Dashboard | Cliente</title>
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
      <header class="header" id="dashboard-header">
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
                <option selected>Ordenar: Seleccionar</option>
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
              <th>Dirección</th>
              <th>Email</th>
              <th>Estado</th>
            </tr>
          </thead>
<tbody>
<?php while ($fila = $resultado->fetch_assoc()): ?>
<tr data-id="<?= $fila['id_cliente'] ?>">
  <td><?= htmlspecialchars($fila['nombre']) ?></td>
  <td><?= htmlspecialchars($fila['direccion']) ?></td>
  <td><?= htmlspecialchars($fila['correo']) ?></td>
  <td>
    <div class="status-actions-container">
      <span class="status <?= $fila['estado'] === 'activo' ? 'active' : 'inactive' ?>">
        <?= ucfirst($fila['estado']) ?>
      </span>
      <div class="action-icons">
        <a href="#" class="action-icon" title="Ver Usuario"><i data-lucide="eye"></i></a>
        <a href="#" class="action-icon" title="Editar"><i data-lucide="edit-2"></i></a>
        <a href="#" class="action-icon" title="Eliminar"><i data-lucide="trash-2"></i></a>
      </div>
    </div>
  </td>
</tr>
<?php endwhile; ?>
</tbody>

          <!-- Formulario de edición oculto -->
          <tr id="edit-form-row" style="display: none;">
            <td colspan="5">
              <form id="edit-form">
                <input type="hidden" id="edit-id">
                <input type="text" id="edit-nombre" placeholder="Nombre">
                <input type="text" id="edit-direccion" placeholder="Dirección">
                <input type="email" id="edit-email" placeholder="Email">
                <button type="submit">Guardar Cambios</button>
                <button type="button" id="cancel-edit">Cancelar</button>
              </form>
            </td>
          </tr>
        </table>
        <div class="pagination-controls">
            <div class="data-count">
                Mostrando <span>1</span> a <span>8</span> de <span>256,000</span> ingresos
            </div>
            <div class="pagination">
                <button class="pagination-button" disabled><i data-lucide="chevron-left"></i> <span>Anterior</span></button>
                <button class="pagination-button page-number active">1</button>
                <button class="pagination-button page-number">2</button>
                <button class="pagination-button page-number">3</button>
                <span class="pagination-ellipsis">...</span>
                <button class="pagination-button page-number">8</button>
                <button class="pagination-button"><span>Siguiente</span> <i data-lucide="chevron-right"></i></button>
            </div>
        </div>
      </section>
    </main>
  </div>
  <script>lucide.createIcons();</script>
  <script src="../../../public/js/admin/clientes.js"></script>
</body>
</html>