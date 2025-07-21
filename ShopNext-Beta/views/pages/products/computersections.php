<?php
// 1. Incluimos el controlador
require_once __DIR__ . '/../../../controllers/product/productController.php';

// 2. Creamos una instancia del controlador y obtenemos los productos
$productController = new ProductController();
$products = $productController->getProductsByCategory('Computadores');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/products/stylescomputers.css">
    <link rel="stylesheet" href="../../../public/css/indexUser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>Computadores | ShopNext</title>
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
        <a href="../../../public/index.html"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="../../../public/index.html">Inicio</a>
      <a href="../../auth/signUp.html">Regístrate</a>
      <a href="../contact.html">Contacto</a>
      <a href="../aboutUs.html">Acerca de</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button>
      <a href="../auth/login.php" class="login-btn">Iniciar Sesión</a>
    </div>
  </div>
</header>
<!-- Sección publicitaria -->
<section class="seccion-computadores">
  <div class="contenido-computadores">
    <h2>LAS MEJORES OFERTAS EN PORTATILES</h2>
  </div>
  <img src="../../../public/img/products/publicidadmsi.png" alt="Monitor Samsung" class="img-computador-izquierda">
  <img src="../../../public/img/products/publicidadasus.png" alt="Laptop Asus" class="img-computador-derecha">
</section>

<section class="seccion-marcas">
  <h2>LAS MEJORES MARCAS</h2>
  <div class="contenedor-marcas">
    </div>
</section>

<section class="section-telefonos">
  <h2>Portatiles</h2>
  <div class="product-grid">

    <?php
    if (!empty($products)) {
        foreach ($products as $product) {
            // Se usa la misma estructura y clases CSS que en index.php
            echo '<div class="product-card">';
            // El enlace ahora lleva a la página de detalle del producto
            echo '  <a href="../productoDetalle.php?id=' . $product['id_producto'] . '" class="product-link">';
            // La ruta de la imagen se ajusta para que funcione desde esta ubicación
            echo '    <img src="../../../public/uploads/products/' . htmlspecialchars($product['ruta_imagen']) . '" alt="' . htmlspecialchars($product['nombre_producto']) . '" class="product-image">';
            echo '    <div class="product-info">';
            echo '      <h3 class="product-name">' . htmlspecialchars($product['nombre_producto']) . '</h3>';
            echo '      <p class="product-price">$' . number_format($product['precio'], 0, ',', '.') . '</p>';
            echo '    </div>';
            echo '  </a>';
            // Se añade el botón de favoritos (wishlist)
            echo '  <button class="wishlist-btn" data-product-id="' . $product['id_producto'] . '"><i class="fa-regular fa-heart"></i></button>';
            echo '</div>';
        }
    } else {
        echo '<p>No hay productos disponibles en esta sección por el momento.</p>';
    }
    ?>
  </div>
</section>
<!-- Footer -->
<footer class="footer-contact">
  <div class="footer-section">
    <img src="../../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
  </div>
  <div class="footer-section">
    <h3>Información</h3>
    <ul>
      <li><a href="../aboutUs.html">Acerca de</a></li>
      <li><a href="../contact.html">Contacto</a></li>
      <li><a href="../../auth/signUp.html">Regístrate</a></li>
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
      <li><a href="#"><img src="../../../public/img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="../../../public/img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>
<script src="../../../public/js/menuHamburguer.js"></script>
</body>
</html>