<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/vendedor/productos.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <title>Dashboard - Mis Productos</title>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="<?php echo BASE_URL; ?>img/logo.svg" alt="Logo" class="logo-img"/>
            </div>
      <ul class="menu">
           <li><a href="index.php?action=seller&page=dashboard"><i data-lucide="layout-dashboard"></i><span>Productos</span></a></li>
            <li class="active"><a href="index.php?action=seller&page=productos"><i data-lucide="package"></i><span>Dashboard</span></a></li>
           <li><a href="index.php?action=seller&page=orders"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="index.php?action=seller&page=upload-product"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="index.php?action=seller&page=income"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
      </ul>
      <div class="user-profile-container">
          <div class="user" id="userProfileBtn">
              <img src="https://i.pravatar.cc/40" alt="user" />
                      <div class="user-info">
                        <p><?php echo htmlspecialchars($data['nombre_vendedor']); ?></p>
                        <small>Vendedor</small>
                    </div>
              <i data-lucide="chevron-down" class="profile-arrow"></i>
          </div>
          <div class="profile-dropdown" id="profileDropdownMenu">
              <a href="?action=logout"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
          </div>
      </div>
    </aside>

        <main class="main">
            <header class="header"><h1>Mis Productos</h1></header>

            <section class="cards">
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

            <section class="table-section">
                <div class="table-header">
                    <h2>Tus Productos Publicados</h2>
                    <a href="<?php echo BASE_URL; ?>index.php?action=seller&page=upload-product" class="btn-add-product">
                        <i data-lucide="plus"></i> Añadir Nuevo Producto
                    </a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['products'])): ?>
                            <?php foreach ($data['products'] as $product): ?>
                                <tr>
                                    <td class="product-cell">
                                        <img src="<?php echo BASE_URL . 'uploads/products/' . htmlspecialchars($product['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($product['nombre_producto']); ?>" class="product-image">
                                        <span><?php echo htmlspecialchars($product['nombre_producto']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['categoria']); ?></td>
                                    <td>$<?php echo number_format($product['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['stock']); ?> unidades</td>
                                    <td class="table-actions">
                                        <div class="action-icons">
                                            <a href="#" class="action-icon edit-btn"><i data-lucide="file-pen-line"></i></a>
                                            <a href="#" class="action-icon delete-btn"><i data-lucide="trash-2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No has publicado ningún producto todavía.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>lucide.createIcons();</script>
    <script src="<?php echo BASE_URL; ?>js/vendedor/productos.js"></script>
</body>
</html>