<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin/clientes.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">    
    <title>Dashboard | Cliente</title>
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
      <header class="header" id="dashboard-header">
        <h1>Hola, <?php echo htmlspecialchars($data['admin_nombre']); ?> 👋</h1>
      </header>

      <section class="cards" id="productos-cards">
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
                                        <a href="#" class="action-icon edit-btn" title="Editar Cliente"><i data-lucide="eye"></i></a>
                                        <a href="#" class="action-icon delete-btn" title="Eliminar Cliente"><i data-lucide="trash-2"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class='text-center'>No se encontraron clientes.</td></tr>
                        <?php endif; ?>
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