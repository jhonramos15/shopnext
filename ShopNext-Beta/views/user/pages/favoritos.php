<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/base.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/user/favoritos.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<header>
  <!-- Header Negro -->
  <div class="header-top">
    <p>Rebajas de Verano: ¡50 % de Descuento!</p>
    <h2>¡Compra Ahora!</h2>
    <select>
      <option value="es">Español</option>
      <option value="en">English</option>
    </select>
  </div>

  <!-- Header Principal -->
  <div class="header-main">
    <!-- Logo Principal -->
    <div class="logo-menu">
      <div class="logo">
        <a href="../indexUser.php"><img src="img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="?action=home">Inicio</a>
      <a href="?action=products">Productos</a>
      <a href="?action=contact">Contacto</a>
    </nav>

    <!-- Buscador -->
    <div class="icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <a href="/shopnext/ShopNext-Beta/views/user/cart/carrito.php" class="header-icon">
        <i class="fa-solid fa-cart-shopping" style="color: #121212;"></i>
      </a>
      <!-- Ícono de usuario -->
      <div class="user-menu-container">
            <i class="fas fa-user user-icon"></i>
            
            <div class="dropdown-content" id="dropdownMenu">
              <a href="?action=account">
                <i class="fas fa-user-circle"></i> <span>Mi Perfil</span>
              </a>
              <a href="/shopnext/ShopNext-Beta/views/user/pages/pedidos.php">
                <i class="fas fa-box"></i> <span>Mis Pedidos</span>
              </a>
              <hr>
              <a href="index.php?action=logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
              </a>
            </div>
        </div>
    </div>
  </div>
</header>
    <main class="wishlist-container">
        <div class="wishlist-header">
            <h1>Mi Lista de Deseos</h1>
            <button class="move-all-btn">Mover todo al Carrito</button>
        </div>

        <?php if (empty($data['favoritos'])): ?>
            <p class="empty-wishlist">Tu lista de deseos está vacía.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($data['favoritos'] as $producto): ?>
                    <div class="product-card" id="product-<?php echo $producto['id_producto']; ?>">
                        <div class="product-image-container">
                            <img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="product-img">
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" data-id="<?php echo $producto['id_producto']; ?>">Añadir al carrito</button>
                                <div class="product-actions">
                                    <button class="remove-from-wishlist" data-id="<?php echo $producto['id_producto']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($producto['nombre_producto']); ?></h4>
                            <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
<!-- Footer -->
     <footer class="footer-contact">
      <div class="footer-section">
          <img src="img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
      </div>

      <div class="footer-section">
          <h3>Información</h3>
          <ul>
              <li><a href="/html/about-us.html">Acerca de</a></li>
              <li><a href="/html/contact.html">Contacto</a></li>
              <li><a href="/html/sign-up.html">Regístrate</a></li>
          </ul>
      </div>

      <div class="footer-section">
          <h3>Soporte</h3>
          <ul>
              <li><a>soporteshopnexts@gmail.com</a></li>
              <li><a>Calle 133 # 123 - 34 Piso 12</a></li>
              <li><a>+57 343 948 9283</a></li>
          </ul>
      </div>

      <div class="footer-section">
          <h3>Contacto</h3>
          <ul>
              <li><a>Redes Sociales</a></li>
              <img src="img/Icon-Twitter.png" alt="Icon Twitter">
              <img src="img/icon-instagram.png" alt="Icon Instagram">
              <img src="img/Icon-Linkedin.png" alt="Icon LinkedIn">
            </ul>
          </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script src="/shopnext/ShopNext-Beta/public/js/user/favorite-actions.js"></script>
<script src="/shopnext/ShopNext-Beta/public/js/shop/carrito.js"></script>
<script src="/shopnext/ShopNext-Beta/public/js/user/wishlist-actions.js"></script>
<script src="/shopnext/ShopNext-Beta/public/js/user/dropdown.js"></script>
</body>
</html>