<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin/clientes.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico" type="image/x-icon">    
    <title>Dashboard Admin | Clientes</title>
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo-container">
        <img src="<?php echo BASE_URL; ?>img/logo.svg" alt="Logo" class="logo-img">
      </div>
      <ul class="menu">
        <li><a href="?action=admin&page=dashboard"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
        <li><a href="?action=admin&page=products"><i data-lucide="box"></i><span>Productos</span></a></li>
        <li class="active"><a href="?action=admin&page=clients"><i data-lucide="users"></i><span>Clientes</span></a></li>
        <li><a href="?action=admin&page=income"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
        <li><a href="?action=admin&page=help"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
        <li><a href="?action=admin&page=sellers"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
        <li><a href="?action=admin&page=reviews"><i data-lucide="star"></i><span>Reseñas</span></a></li>
      </ul>
      <div class="user-profile-container">
        <div class="user" id="userProfileBtn">
          <img src="https://i.pravatar.cc/40" alt="user" />
          <div class="user-info">
            <p><?php echo htmlspecialchars($data['admin_nombre'] ?? 'Admin'); ?></p>
            <small>Administrador</small>
          </div>
          <i data-lucide="chevron-down" class="profile-arrow"></i>
        </div>
        <div class="profile-dropdown" id="profileDropdownMenu">
          <a href="?action=logout"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
        </div>
      </div>
    </aside>
    <main class="main">
      <header class="header" id="dashboard-header">
        <h1>Hola, <?php echo htmlspecialchars($data['admin_nombre']); ?> 👋</h1>
      </header>
      <section class="cards" id="clientes-cards">
        <div class="card">
          <i data-lucide="users-2"></i>
          <div>
            <h3>Clientes Totales</h3>
            <p>
              <?php echo number_format($data['stats']['total_usuarios']); ?>
              <span class="percentage <?php echo ($data['stats']['cambio_porcentual'] >= 0) ? 'positive' : 'neutral'; ?>">
                <?php echo ($data['stats']['cambio_porcentual'] >= 0 ? '+' : '') . number_format($data['stats']['cambio_porcentual'], 1); ?>%
              </span>
            </p>
          </div>
        </div>
        <div class="card">
          <i data-lucide="award"></i>
          <div>
            <h3>Miembros</h3>
            <p>
              <?php echo number_format($data['stats']['total_usuarios']); ?>
              <span class="percentage <?php echo ($data['stats']['cambio_porcentual'] >= 0) ? 'positive' : 'neutral'; ?>">
                <?php echo ($data['stats']['cambio_porcentual'] >= 0 ? '+' : '') . number_format($data['stats']['cambio_porcentual'], 1); ?>%
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
        </div>
        <table>
          <thead>
            <tr>
              <th>Nombre Cliente</th>
              <th>Email</th>
              <th>Fecha de Registro</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data['clients'])): ?>
              <?php foreach ($data['clients'] as $client): ?>
                <tr data-id="<?php echo htmlspecialchars($client['id_usuario']); ?>">
                  <td><?php echo htmlspecialchars($client['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($client['correo_usuario']); ?></td>
                  <td><?php echo date("d/m/Y", strtotime($client['fecha_registro'])); ?></td>
                  <td>
                    <span class="status <?php echo ($client['estado'] === 'activo') ? 'active' : 'inactive'; ?>">
                      <?php echo ucfirst(htmlspecialchars($client['estado'])); ?>
                    </span>
                  </td>
                  <td class="table-actions">
                    <a href="#" class="action-icon edit-btn" title="Editar Cliente"><i data-lucide="edit-2"></i></a>
                    <a href="#" class="action-icon delete-btn" title="Eliminar Cliente"><i data-lucide="trash-2"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
            <tr><td colspan="5" style="text-align: center;">No se encontraron clientes.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
      <div id="edit-modal-overlay" class="modal-overlay" style="display:none;">
        <div class="modal-content">
          <h2>Editar Cliente</h2>
          <form id="edit-client-form">
            <input type="hidden" id="edit-client-id" name="id_usuario">
            <div class="form-group">
              <label for="edit-nombre">Nombre</label>
              <input type="text" id="edit-nombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="edit-email">Correo Electrónico</label>
              <input type="email" id="edit-email" name="correo_usuario" required>
            </div>
            <div class="form-group">
              <label for="edit-estado">Estado</label>
                <select id="edit-estado" name="estado" required>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="modal-actions">
              <button type="button" class="btn-cancel" id="cancel-edit-btn">Cancelar</button>
              <button type="submit" class="btn-save">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- 2. Añadimos el objeto App con la URL base -->
    <script>
        const App = { baseUrl: '<?php echo BASE_URL; ?>' };
        lucide.createIcons();
    </script>
    <!-- 3. Cargamos tu script de clientes -->
    <script src="<?php echo BASE_URL; ?>js/admin/clientes.js"></script>
</body>
</html>
