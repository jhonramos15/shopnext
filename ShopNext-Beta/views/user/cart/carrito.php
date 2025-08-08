<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras | ShopNext</title>
    <link rel="stylesheet" href="css/shop/carrito.css"> 
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
      <a href="../../../public/index.php">Inicio</a>
      <a href="../../pages/products/category.php">Productos</a>
      <a href="../pages/contact.php">Contacto</a>
    </nav>

    <!-- Buscador -->
    <div class="icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <!-- Favoritos -->
      <a href="../pages/favoritos.php"><button class="icon-btn"><i class="fa-solid fa-heart"></i></button></a>
      <!-- Ícono de usuario -->
        <div class="user-menu-container">
            <i class="fas fa-user user-icon"></i>
            
            <div class="dropdown-content" id="dropdownMenu">
              <a href="/shopnext/ShopNext-Beta/views/pages/account.php">
                <i class="fas fa-user-circle"></i> <span>Mi Perfil</span>
              </a>
              <a href="/shopnext/ShopNext-Beta/views/user/pages/pedidos.php">
                <i class="fas fa-box"></i> <span>Mis Pedidos</span>
              </a>
              <hr>
              <a href="/shopnext/ShopNext-Beta/controllers/logout.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
              </a>
            </div>
        </div> 
    </div>
  </div>
</header>

  <main class="cart-container">
    <h2>Tu Carrito</h2>

    <div class="cart-header">
        <div>Producto</div>
        <div>Precio</div>
        <div>Cantidad</div>
        <div>Subtotal</div>
    </div>

    <div id="cart-items">
        <?php
        $total_general = 0;
        if (count($items_del_carrito) > 0) {
            foreach ($items_del_carrito as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $total_general += $subtotal;
        ?>
            <div class="cart-item" data-id="<?php echo $item['id_producto_carrito']; ?>">
    <div class="product-details">
         <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($item['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>" class="product-image">
        <span class="product-name"><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
    </div>
    <div class="product-price" data-price="<?php echo $item['precio']; ?>">$<?php echo number_format($item['precio']); ?></div>
    <div class="product-quantity">
        <div class="quantity-selector">
            <button class="quantity-btn decrease-qty">-</button>
            <input type="number" class="quantity-input" value="<?php echo $item['cantidad']; ?>" readonly>
            <button class="quantity-btn increase-qty">+</button>
        </div>
    </div>
    <div class="product-subtotal">$<?php echo number_format($subtotal); ?></div>

    <div class="remove-action">
        <button class="remove-item-btn" title="Eliminar producto" onclick="eliminarDelCarrito(<?php echo $item['id_producto']; ?>)">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
        <?php
            }
        } else {
            echo "<p class='empty-cart'>Tu carrito está vacío.</p>";
        }
        ?>
    </div>

    <div class="cart-bottom">
    <div class="cart-actions">
        <a href="../../../public/index.php" class="btn btn-outline">Volver a la Tienda</a>
        <button type="button" class="btn btn-danger btn-vaciar-carrito">Vaciar Carrito</button>
    </div>
    
    <div class="summary-section">
        <h3 class="summary-title">Resumen del Carrito</h3>
        <div class="summary-item total-item">
            <span>Total:</span>
            <strong id="cart-total">$<?php echo number_format($total_general); ?></strong>
        </div>
        <a href="checkout.php" class="btn btn-primary btn-checkout">Finalizar Compra</a>
    </div>
</div>
  </main>

<footer class="footer-contact">
  <div class="footer-section">
    <img src="img/logo-positivo.png" alt="ShopNext Logo" class="footer-logo">
  </div>
  <div class="footer-section">
    <h3>Información</h3>
    <ul>
      <li><a href="../views/pages/aboutUs.html">Acerca de</a></li>
      <li><a href="../views/pages/contact.html">Contacto</a></li>
      <li><a href="../views/auth/signUp.html">Regístrate</a></li>
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
    <ul class="social-icons">
      <li><a href="#"><img src="img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const CART_API_URL = '<?php echo BASE_URL; ?>index.php?action=cart-api';
</script>

<script src="<?php echo BASE_URL; ?>js/common/alertas.js"></script>
<script src="<?php echo BASE_URL; ?>js/shop/carrito.js"></script>
<script src="<?php echo BASE_URL; ?>js/user/dropdown.js"></script>

</body>
</html>