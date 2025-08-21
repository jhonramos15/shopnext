<?php
// En: views/admin/adminReviewsView.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Reseñas</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin/reviews.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico" type="image/x-icon">
    <script src="https://unpkg.com/lucide@latest"></script>
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
            <li><a href="index.php?action=admin&page=sellers"><i data-lucide="user-check"></i><span>Vendedores</span></a></li>
            <li class="active"><a href="index.php?action=admin&page=reviews"><i data-lucide="star"></i><span>Reseñas</span></a></li>
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
            <header class="header"><h1>Gestión de Reseñas</h1></header>

            <section class="cards">
                <div class="card">
                    <h3>Total Reseñas</h3>
                    <p><?php echo number_format($data['stats']['total_resenas']); ?></p>
                </div>
                <div class="card">
                    <h3>Calificación Promedio</h3>
                    <p><?php echo number_format($data['stats']['calificacion_promedio'], 1); ?> ★</p>
                </div>
                <div class="card">
                    <h3>Pendientes de Aprobar</h3>
                    <p><?php echo number_format($data['stats']['resenas_pendientes']); ?></p>
                </div>
            </section>

            <section class="table-section">
                <div class="table-header"><h2>Todas las Reseñas</h2></div>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['reviews'])): ?>
                            <?php foreach ($data['reviews'] as $review): ?>
                                <tr data-id="<?php echo htmlspecialchars($review['id_resena']); ?>">
                                    <td><?php echo htmlspecialchars($review['nombre_producto'] ?? 'Producto Eliminado'); ?></td>
                                    <td><?php echo htmlspecialchars($review['nombre_cliente'] ?? 'Cliente Eliminado'); ?></td>
                                    <td>
                                        <div class="stars">
                                            <?php for($i = 0; $i < 5; $i++): ?>
                                                <i data-lucide="star" class="<?php echo ($i < $review['puntuacion']) ? 'filled' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="comment"><?php echo htmlspecialchars($review['comentario']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($review['fecha_creacion'])); ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower(htmlspecialchars($review['estado'])); ?>">
                                            <?php echo ucfirst(htmlspecialchars($review['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="#" class="action-icon approve-btn" title="Aprobar"><i data-lucide="check-circle"></i></a>
                                        <a href="#" class="action-icon reject-btn" title="Rechazar"><i data-lucide="x-circle"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center;">No hay reseñas para moderar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>lucide.createIcons();</script>
    <script>
        const App = { baseUrl: '<?php echo BASE_URL; ?>' };
        lucide.createIcons();
    </script>
    <script src="<?php echo BASE_URL; ?>js/admin/reviews.js"></script>
</body>
</html>