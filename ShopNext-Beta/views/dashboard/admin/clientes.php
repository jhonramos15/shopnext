<?php
// --- INICIO DEL CÓDIGO PHP PARA OBTENER DATOS ---

// Conexión a la base de datos (ajusta tus credenciales si es necesario)
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}

// Consulta para obtener el total de usuarios (clientes)
$total_users_query = "SELECT COUNT(*) as total FROM usuario";
$total_users_result = $conexion->query($total_users_query);
$total_usuarios = $total_users_result->fetch_assoc()['total'];

// Consulta para obtener los usuarios registrados en los últimos 7 días
$new_users_query = "SELECT COUNT(*) as nuevos_usuarios FROM usuario WHERE fecha_registro >= CURDATE() - INTERVAL 7 DAY";
$new_users_result = $conexion->query($new_users_query);
$nuevos_usuarios = $new_users_result->fetch_assoc()['nuevos_usuarios'];

// Calcular el cambio porcentual
$usuarios_anteriores = $total_usuarios - $nuevos_usuarios;
$cambio_porcentual = 0; // Inicializar en 0

if ($usuarios_anteriores > 0) {
    $cambio_porcentual = ($nuevos_usuarios / $usuarios_anteriores) * 100;
} elseif ($nuevos_usuarios > 0) {
    $cambio_porcentual = 100; // Si no había usuarios y ahora sí, es un 100% de aumento
}
// --- FIN DEL CÓDIGO PHP ---
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
            <i data-lucide="users-2"></i>
            <div>
                <h3>Clientes Totales</h3>
                <p>
                    <?php echo number_format($total_usuarios); ?>
                    <span class="percentage <?php echo ($cambio_porcentual >= 0) ? 'positive' : 'neutral'; ?>">
                        <?php echo ($cambio_porcentual >= 0 ? '+' : '') . number_format($cambio_porcentual, 1); ?>%
                    </span>
                </p>
            </div>
        </div>
        <div class="card">
            <i data-lucide="award"></i> <div>
                <h3>Miembros</h3>
                <p>
                    <?php echo number_format($total_usuarios); ?>
                    <span class="percentage <?php echo ($cambio_porcentual >= 0) ? 'positive' : 'neutral'; ?>">
                        <?php echo ($cambio_porcentual >= 0 ? '+' : '') . number_format($cambio_porcentual, 1); ?>%
                    </span>
                </p>
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
            <th>Email</th>
            <th>Fecha de Registro</th>
            <th>Estado</th> <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // ----- AÑADE ESTA LÍNEA AQUÍ -----
    // Ejecuta la consulta para obtener todos los clientes y la guarda en $query_resultado
    $sql_clientes = "SELECT u.id_usuario, c.nombre, u.correo_usuario, u.estado, u.fecha_registro FROM usuario u JOIN cliente c ON u.id_usuario = c.id_usuario WHERE u.rol = 'cliente'";
    $query_resultado = $conexion->query($sql_clientes);

    // Ahora el 'if' ya puede usar la variable porque acaba de ser creada
    if ($query_resultado && $query_resultado->num_rows > 0) {
        while ($fila = $query_resultado->fetch_assoc()) {
    ?>
            <tr data-id="<?php echo htmlspecialchars($fila['id_usuario']); ?>">
                <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                <td><?php echo htmlspecialchars($fila['correo_usuario']); ?></td>
                <td><?php echo htmlspecialchars($fila['fecha_registro']); ?></td>
                <td>
                    <span class="status <?php echo ($fila['estado'] === 'activo') ? 'active' : 'inactive'; ?>">
                        <?php echo ucfirst(htmlspecialchars($fila['estado'])); ?>
                    </span>
                </td>
                <td class="table-actions">
                    <a href="#" class="action-icon edit-btn" title="Editar Cliente"><i data-lucide="eye"></i></a>
                    <a href="#" class="action-icon delete-btn" title="Eliminar Cliente"><i data-lucide="trash-2"></i></a>
                </td>
            </tr>
    <?php
        } // Fin del while
    } else {
        echo "<tr><td colspan='5' class='text-center'>No se encontraron clientes.</td></tr>";
    }
    ?>
</tbody>
</table>
</section>
</main>
  </div>
<!-- Modal de edición -->
<div id="edit-modal-overlay" style="display: none;">
  <div id="edit-modal">
    <form id="edit-form">
      <input type="hidden" id="edit-id">
      <input type="text" id="edit-nombre" placeholder="Nombre">
      <input type="text" id="edit-direccion" placeholder="Dirección">
      <input type="email" id="edit-email" placeholder="Correo">
      <div class="edit-form-buttons">
        <button type="submit">Guardar</button>
        <button type="button" id="cancel-edit">Cancelar</button>
      </div>
    </form>
  </div>
</div>
<script>lucide.createIcons();</script>
<script src="../../../public/js/admin/clientes.js"></script>
</body>
</html>