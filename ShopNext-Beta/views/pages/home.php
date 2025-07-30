<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/shop/home.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/base.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <title>ShopNext | Inicio</title>
</head>
<body>
<header>
  <!-- Header Oscuro -->
  <div class="header-top">
    <p>Rebajas de Verano: ¡50 % de Descuento!</p>
    <h2>¡Compra Ahora!</h2>
  </div>
  <!-- Logo Principal -->
  <div class="header-main">
    <div class="logo">
      <a href="/shopnext/ShopNext-Beta/public/index.php"><img src="/shopnext/ShopNext-Beta/public/img/logo.svg" alt="ShopNext"></a>
    </div>
    <!-- Secciones -->
    <nav class="nav-links">
        <a href="/shopnext/ShopNext-Beta/public/index.php">Inicio</a>

        <?php if ($usuario_logueado): ?>
            <a href="?action=products">Productos</a>
            <a href="?action=contact">Contacto</a>
        <?php else: ?>
            <a href="?action=signup">Regístrate</a>
            <a href="?=contact">Contacto</a>
            <a href="?action=about">Acerca de</a>
        <?php endif; ?>
    </nav>
    <!-- Buscador -->
    <div class="header-icons">
      <form class="buscador" id="search-form">
        <input type="text" id="search-input" placeholder="¿Qué estás buscando?">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
      <div id="search-results"></div>
      <!-- Favoritos y Carrito, logueados -->
      <?php if ($usuario_logueado): ?>
        <a href="/shopnext/ShopNext-Beta/views/user/pages/favoritos.php" title="Favoritos"><i class="fa-solid fa-heart"></i></a>
        <a href="/shopnext/ShopNext-Beta/views/user/cart/carrito.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
        <!-- Ícono de usuario, solo si está logueado -->
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
              <a href="index.php?action=logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
              </a>
            </div>
        </div>
        <!-- Favoritos y Carrito, no logueados -->
      <?php else: ?>
        <a href="/shopnext/ShopNext-Beta/views/auth/login.php" title="Favoritos"><i class="fa-solid fa-heart"></i></a>
        <a href="/shopnext/ShopNext-Beta/views/auth/login.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="/shopnext/ShopNext-Beta/views/auth/login.php" class="login-btn">Iniciar Sesión</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<!-- Separador entre secciones -->
<section></section>
<!-- Carrusel -->
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <!-- Slide 1 -->
    <div class="swiper-slide">
      <img src="img/carousel/audi.webp" alt="Banner 1">
    </div>

    <!-- Slide 2 -->
    <div class="swiper-slide">
      <img src="img/carousel/ps5.webp" alt="Banner 2">
    </div>

    <!-- Slide 3 -->
    <div class="swiper-slide">
      <img src="img/carousel/apple1.jpg" alt="Banner 3">
    </div>

    <!-- Slide 4 -->
    <div class="swiper-slide">
      <img src="img/carousel/samsunggalaxynote8.jpg" alt="Banner 4">
    </div>

        <!-- Slide 5 -->
    <div class="swiper-slide">
      <img src="img/carousel/puma.jpg" alt="Banner 5">
    </div>

        <!-- Slide 6 -->    
    <div class="swiper-slide">
      <img src="img/carousel/chair_ikea.png" alt="Banner 6">
    </div>

        <!-- Slide 7 -->    
    <div class="swiper-slide">
      <img src="img/carousel/iphone16.jpg" alt="Banner 7">
    </div>
  
        <!-- Slide 8 -->    
    <div class="swiper-slide">
      <img src="img/carousel/amd.jpg" alt="Banner 8">
    </div>
  </div>      

  <!-- Botones Swiper -->
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>

  <!-- Paginación -->
  <div class="swiper-pagination"></div>
</div>
<!-- Secciones principales -->
<section class="seccion-categorias">
  <p class="etiqueta">Productos por Sección</p>
  <h2 class="titulo">Todas nuestras secciones</h2>

  <div class="contenedor-categorias" id="categorias">
    <div class="categoria">
      <a href="../views/pages/products/womansection.php"><i class="fa-solid fa-person-dress"></i></a>
      <p>Ropa Femenina</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/mensection.php"><i class="fa-solid fa-person"></i></a>
      <p>Ropa Masculina</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/computersections.php"><i class="fa-solid fa-computer"></i></a>
      <p>Computadores</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/videogamessection.php"><i class="fas fa-gamepad"></i></a>
      <p>Videojuegos</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/sportsections.php"><i class="fa-solid fa-baseball-bat-ball"></i></a>
      <p>Deportes</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/homesections.php"><i class="fa-solid fa-star-of-life"></i></a>
      <p>Hogar & Belleza</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/phonesections.php"><i class="fa-solid fa-mobile-button"></i></a>
      <p>Celulares</p>
    </div>            
  </div>
</section>
<!-- Últimos Productos -->
<section class="latest-products-section">
  <div class="section-header">
    <div class="title-container">
      <span class="title-badge"></span>
      <h2>
        <span class="title-part-1">Últimos</span>
        <span class="title-part-2">Productos</span>
      </h2>
    </div>
  </div>  
  <div class="our-products">
    <h2>Nuestros Productos</h2>
    <div class="product-grid">
        <?php if (!empty($latestProducts)): ?>
            <?php foreach ($latestProducts as $product): ?>
              <div class="product-card">
                    <div class="product-image-wrapper">
                        <?php
                            $base_url = '/shopnext/ShopNext-Beta/';
                            $image_path = $base_url . 'public/uploads/products/' . htmlspecialchars($product['ruta_imagen']);
                        ?>
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['nombre_producto']); ?>">
                        </div>
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($product['nombre_producto']); ?></h3>
                        <div class="product-price">
                            $<?php echo htmlspecialchars(number_format($product['precio'])); ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos para mostrar.</p>
        <?php endif; ?>

    </div>
</div>
  </div>
</section>
<section></section>
<!-- Secciones Destacadas -->
<section class="seccion-categorias">
  <div class="category-header-simple">
    <div class="title-badge"></div>
      <div class="title-text">
        <p class="category-subtitle">Este Mes</p>
        <h2 class="category-title">Explora por Categoría</h2>
      </div>
    </div>
    <div class="categoria-grid">
      <a href="/shopnext/ShopNext-Beta/views/pages/products/phonesections.php" class="categoria-item">
        <div class="icon"><i class="fas fa-mobile-alt"></i></div>
        <p>Teléfonos</p>
      </a>
      <a href="/shopnext/ShopNext-Beta/views/pages/products/computersections.php" class="categoria-item">
        <div class="icon"><i class="fas fa-laptop"></i></div>
        <p>Computadores</p>
      </a>
      <a href="#" class="categoria-item"> <div class="icon"><i class="fas fa-headphones"></i></div>
        <p>Audífonos</p>
      </a>
      <a href="/shopnext/ShopNext-Beta/views/pages/products/videogamessection.php" class="categoria-item">
        <div class="icon"><i class="fas fa-gamepad"></i></div>
        <p>Videojuegos</p>
      </a>
    </div>
  </div>
</section>
<section></section>
<section class="best-selling-section">
  <div class="section-header">
    <div>
      <div class="badge2">Este Mes</div>
      <h2 class="title2">Más Vendidos</h2>
    </div>
  </div>
  <div class="products-grid">
    <?php if (!empty($bestSellingProducts)): ?>
    <?php foreach ($bestSellingProducts as $product): ?>
    <div class="product-card">
      <div class="product-image-wrapper">
        <?php
          $base_url = '/shopnext/ShopNext-Beta/';
          $image_path = $base_url . 'public/uploads/products/' . htmlspecialchars($product['ruta_imagen']);
        ?>
        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['nombre_producto']); ?>">                
        <button class="add-to-cart-btn">
          <i class="fa-solid fa-cart-shopping"></i>
          Añadir al Carrito
        </button>
      </div>
      <div class="product-details">
        <h3><?php echo htmlspecialchars($product['nombre_producto']); ?></h3>
        <p class="product-price">$<?php echo htmlspecialchars(number_format($product['precio'])); ?></p>
      </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p>Aún no hay suficientes ventas para mostrar los productos más vendidos.</p>
    <?php endif; ?>
  </div>
</section>
<!-- Separador -->
<section></section>
  <section class="music-experience">
  <div class="promo-container">
    <div class="promo-content">
    <p class="promo-category">Categorías</p>
      <h2>Mejora Tu Experiencia Musical</h2>
      <div class="promo-countdown">
        <div class="time-box">
          <span id="promo-days">00</span>
          <small>Días</small>
        </div>
        <div class="time-box">
          <span id="promo-hours">00</span>
          <small>Horas</small>
        </div>
        <div class="time-box">
          <span id="promo-minutes">00</span>
          <small>Minutos</small>
        </div>
        <div class="time-box">
          <span id="promo-seconds">00</span>
          <small>Segundos</small>
        </div>
      </div>
      <a href="#" class="promo-button">¡Comprar Ahora!</a>
    </div>
    <div class="promo-image">
      <img src="img/jbl_speakers.png" alt="JBL Speaker">
    </div>
  </div>
</section>
<section class="new-arrivals">
  <div class="container">
    <p class="featured-text">Destacado</p>
    <h2>Nuevos Lanzamientos</h2>
    <div class="arrivals-grid">
      <div class="product-card large-card">
        <a href="#" class="product-link">
          <img src="img/ps5-slim.jpeg" alt="PlayStation 5" class="product-image" style="object-fit: contain !important; padding: 20px;">
          <div class="product-info">
            <h3>PlayStation 5</h3>
            <p>La versión en Blanco y Negro de la PS5 ya está a la venta.</p>
            <span>Comprar Ahora</span>
          </div>
        </a>
      </div>
      <div class="product-card top-right-card">
        <a href="#" class="product-link">
          <img src="img/women-pub.png" alt="Colección de Mujer" class="product-image">
          <div class="product-info">
            <h3>Colección de Mujer</h3>
            <p>Colecciones destacadas que te dan otra vibra.</p>
            <span>Comprar Ahora</span>
          </div>
        </a>
      </div>
      <div class="bottom-right-wrapper">
        <div class="product-card">
          <a href="#" class="product-link">
            <img src="img/speaker-pub.png" alt="Altavoces Inteligentes" class="product-image">
            <div class="product-info">
              <h3>Altavoces</h3>
              <p>Altavoces inalámbricos de Amazon.</p>
              <span>Comprar Ahora</span>
            </div>
          </a>
        </div>
        <div class="product-card">
          <a href="#" class="product-link">
            <img src="img/gucci-pub.png" alt="Perfume Gucci" class="product-image">
            <div class="product-info">
              <h3>Perfume</h3>
              <p>GUCCI INTENSE OUD EDP.</p>
              <span>Comprar Ahora</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<section></section>
<!-- Categoria de beneficios cerca al footer -->
<div class="benefits-container">
  <div class="benefit-box">
    <div class="benefit-icon">
      <img src="img/security.png" alt="Security" />
    </div>
    <h3>ENVÍO RÁPIDO Y GRATUITO</h3>
    <p>Envíos con descuento desde los 140.000 COP</p>
  </div>
  <div class="benefit-box">
    <div class="benefit-icon">
      <img src="img/services.png" alt="Customer Support" />
    </div>
    <h3>ATENCIÓN AL CLIENTE 24/7</h3>
    <p>Soporte amigable 24/7</p>
  </div>
  <div class="benefit-box">
    <div class="benefit-icon">
      <img src="img/support.png" alt="Guarantee" />
    </div>
    <h3>GARANTÍA DE DEVOLUCIÓN</h3>
    <p>30 días para tu reembolso</p>
  </div>
</div>
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
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/common/alertas.js"></script> 
<script src="js/shop/carrito.js"></script>
<script src="js/user/favoritos.js"></script>   
<script src="js/common/menu-hamburguer.js"></script>
<script src="js/shop/search.js"></script> 
<script src="js/shop/index.js"></script> 
</body>
</html>