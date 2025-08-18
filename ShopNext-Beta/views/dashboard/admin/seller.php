<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin/vendedores.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <title>Dashboard | Vendedores</title>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="img/logo.svg" alt="Logo" class="logo-img">
            </div>
            <ul class="menu">
            <li><a href="index.php?action=admin&page=dashboard"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
            <li><a href="index.php?action=admin&page=products"><i data-lucide="box"></i><span>Productos</span></a></li>
            <li><a href="index.php?action=admin&page=clients"><i data-lucide="users"></i><span>Clientes</span></a></li>
            <li><a href="index.php?action=admin&page=income"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
            <li><a href="index.php?action=admin&page=help"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
            <li class="active"><a href="index.php?action=admin&page=sellers"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
            <li><a href="index.php?action=admin&page=reviews"><i data-lucide="star"></i><span>Reseñas</span></a></li>
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
                    <a href="?action=logout"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
                </div>
            </div>
        </aside>

        <main class="main">
            <header class="header" id="vendedores-header">
                <h1>Hola, Brayan 👋</h1>
                </header>

            <section class="cards" id="vendedores-cards">
                <div class="card">
                    <i data-lucide="user-check"></i>
                    <div>
                        <h3>Vendedores Totales</h3>
                        <p><?php echo number_format($data['stats']['total_vendedores']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="trending-up"></i>
                    <div>
                        <h3>Ventas del Mes</h3>
                        <p>$<?php echo number_format($data['stats']['ventas_mes'], 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="award"></i>
                    <div>
                        <h3>Mejor Vendedor</h3>
                        <p><?php echo htmlspecialchars($data['stats']['mejor_vendedor_nombre']); ?></p>
                    </div>
                </div>
            </section>

            <section class="table-section">
                <div class="table-header">
                    <h2>Todos los Vendedores</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Vendedor</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['sellers'])): ?>
                            <?php foreach ($data['sellers'] as $seller): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($seller['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['correo_usuario']); ?></td>
                                    <td>
                                        <span class="status <?php echo ($seller['estado'] === 'activo') ? 'active' : 'inactive'; ?>">
                                            <?php echo ucfirst(htmlspecialchars($seller['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon edit-btn"><i data-lucide="edit-2"></i></a>
                                        <a href="#" class="action-icon delete-btn"><i data-lucide="trash-2"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No se encontraron vendedores.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <div id="edit-modal-overlay" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h2>Editar Vendedor</h2>
            <form id="edit-form">
                <input type="hidden" id="edit-id-usuario" name="id_usuario">
                <div class="form-group">
                    <label for="edit-nombre">Nombre</label>
                    <input type="text" id="edit-nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="edit-correo">Correo Electrónico</label>
                    <input type="email" id="edit-correo" name="correo" required>
                </div>
                 <div class="form-group">
                    <label for="edit-telefono">Teléfono</label>
                    <input type="tel" id="edit-telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="edit-estado">Estado</label>
                    <select id="edit-estado" name="estado">
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

    <script>lucide.createIcons();</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../public/js/admin/vendedores.js"></script>
</body>
</html>