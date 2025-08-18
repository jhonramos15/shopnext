<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Productos</title>
    <link rel="stylesheet" href="css/admin/productos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="img/logo.svg" alt="Logo" class="logo-img">
            </div>
            <ul class="menu">
            <li><a href="index.php?action=admin&page=dashboard"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
            <li class="active"><a href="index.php?action=admin&page=products"><i data-lucide="box"></i><span>Productos</span></a></li>
            <li><a href="index.php?action=admin&page=clients"><i data-lucide="users"></i><span>Clientes</span></a></li>
            <li><a href="index.php?action=admin&page=income"><i data-lucide="bar-chart-2"></i><span>Ingresos</span></a></li>
            <li><a href="index.php?action=admin&page=help"><i data-lucide="help-circle"></i><span>Ayuda</span></a></li>
            <li><a href="index.php?action=admin&page=sellers"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
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
            <header class="header">
                <h1>Todos los Productos</h1>
            </header>

            <section class="cards" id="productos-cards">
                <div class="card">
                    <i data-lucide="package"></i>
                    <div>
                        <h3>Todos los Productos</h3>
                        <p><?php echo number_format($data['stats']['total_productos']); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="dollar-sign"></i>
                    <div>
                        <h3>Valor del Inventario</h3>
                        <p>$<?php echo number_format($data['stats']['valor_inventario'] ?? 0, 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i data-lucide="package-x"></i>
                    <div>
                        <h3>Agotados</h3>
                        <p><?php echo number_format($data['stats']['productos_agotados']); ?></p>
                    </div>
                </div>
            </section>

<section class="table-section" id="productos-table">
    <div class="table-header"><h2>Productos en la Tienda</h2></div>
    <table>
        <thead>
            <tr>
                <th>Nombre Producto</th>
                <th>Vendedor</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
                        <?php if (!empty($data['products'])): ?>
                            <?php foreach ($data['products'] as $product): ?>
                                <tr data-id="<?php echo htmlspecialchars($product['id_producto']); ?>">
                                    <td><?php echo htmlspecialchars($product['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($product['nombre_vendedor']); ?></td>
                                    <td><?php echo htmlspecialchars($product['categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                    <td>$<?php echo number_format($product['precio'], 2); ?></td>
                                    <td><span class="status <?php echo ($product['stock'] > 0) ? 'active' : 'inactive'; ?>"><?php echo ($product['stock'] > 0) ? 'Publicado' : 'Agotado'; ?></span></td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon edit-btn" title="Editar"><i data-lucide="edit-2"></i></a>
                                        <a href="#" class="action-icon delete-btn" title="Eliminar"><i data-lucide="trash-2"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
            <div id="edit-modal-overlay" class="modal-overlay" style="display:none;">
                <div class="modal-content">
                    <h2>Editar Producto</h2>
                    <form id="edit-product-form">
                        <input type="hidden" id="edit-product-id" name="id_producto">
                        <div class="form-group"><label for="edit-nombre">Nombre</label><input type="text" id="edit-nombre" name="nombre_producto" required></div>
                        <div class="form-group"><label for="edit-categoria">Categoría</label><input type="text" id="edit-categoria" name="categoria" required></div>
                        <div class="form-group"><label for="edit-precio">Precio</label><input type="number" id="edit-precio" name="precio" step="0.01" required></div>
                        <div class="form-group"><label for="edit-stock">Stock</label><input type="number" id="edit-stock" name="stock" required></div>
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

    <script>
        const App = {
            baseUrl: '<?php echo BASE_URL; ?>'
        };
        lucide.createIcons();
    </script>

    <script src="<?php echo BASE_URL; ?>js/admin/productos.js"></script>
    <script src="<?php echo BASE_URL; ?>js/common/main.js"></script>
    </body>
</body>
</html>