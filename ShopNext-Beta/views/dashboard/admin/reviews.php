<?php
// En: views/admin/adminReviewsView.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Reseñas</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin/vendedores.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico" type="image/x-icon">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <ul class="menu">
                <li><a href="<?php echo BASE_URL; ?>index.php?action=admin&page=dashboard"><span>Dashboard</span></a></li>
                <li class="active"><a href="<?php echo BASE_URL; ?>index.php?action=admin&page=reviews"><span>Reseñas</span></a></li>
                </ul>
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
            <tr>
                <td><?php echo htmlspecialchars($review['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($review['nombre_cliente']); ?></td>
                
                <td><?php echo str_repeat('★', $review['puntuacion']) . str_repeat('☆', 5 - $review['puntuacion']); ?></td>
                
                <td class="comment"><?php echo htmlspecialchars($review['comentario']); ?></td>
                
                <td><?php echo date("d/m/Y", strtotime($review['fecha_creacion'])); ?></td>
                
                <td><span class="status <?php echo strtolower(htmlspecialchars($review['estado'])); ?>"><?php echo ucfirst(htmlspecialchars($review['estado'])); ?></span></td>
                <td class="table-actions">
                    <a href="#" class="action-icon approve-btn"><i data-lucide="check-circle"></i></a>
                    <a href="#" class="action-icon reject-btn"><i data-lucide="x-circle"></i></a>
                    <a href="#" class="action-icon delete-btn"><i data-lucide="trash-2"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">No hay reseñas para mostrar.</td></tr>
    <?php endif; ?>
</tbody>
                </table>
            </section>
        </main>
    </div>
    <script>lucide.createIcons();</script>
    <script src="<?php echo BASE_URL; ?>js/admin/script-admin.js"></script>
</body>
</html>