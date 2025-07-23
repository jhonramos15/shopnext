<?php
// 1. Incluimos el controlador de productos
require_once __DIR__ . '/../../../controllers/product/productController.php';

// 2. Creamos una instancia del controlador y obtenemos los productos de la categoría "Ropa Masculina"
$productController = new ProductController();
$products = $productController->getProductsByCategory('Videojuegos');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/products/stylesmen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>Videojuegos | ShopNext</title>
</head>
<body>
<header>
  <div class="header-top">
    <p>Nueva Colección de Verano</p>
    <h2>¡Descúbrela Ahora!</h2>
    <select>
      <option value="es">Español</option>
      <option value="en">English</option>
    </select>
  </div>

  <div class="header-main">
    <div class="logo-menu">
      <div class="logo">
        <a href="#"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <nav class="nav-links" id="navMenu">
      <a href="../../../public/index.php">Inicio</a>
      <a href="category.php">Productos</a>
      <a href="../../user/pages/contact.php">Contacto</a>
    </nav>

    <div class="header-icons">
      <a href="../../user/cart/carrito.php"><button class="icon-btn"><i class="fa-solid fa-heart"></i></button></a>
      <a href="../../user/pages/favoritos.php"><button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
    </div>
  </div>
</header>

<div class="header-top custom-header-banner">
    <div class="banner-text-content">
        <p>DOMINA EL JUEGO</p>
        <h2>Los últimos lanzamientos y las mejores consolas te esperan.</h2>
    </div>
    <div class="banner-image-right">
        <img src="../../../public/img/header-videogames.png" alt="Header videojuegos">
    </div>
</div>
</section>

<section class="seccion-marcas">
  <h2>PLATAFORMAS DISPONIBLES</h2>
  <div class="contenedor-marcas">
    <div class="marca"><img src="../../../public/img/products/logo-ps.png" alt="PlayStation"></div>
    <div class="marca"><img src="../../../public/img/products/logo-xbox.png" alt="Xbox"></div>
    <div class="marca"><img src="../../../public/img/products/logo-switch.png" alt="Nintendo Switch"></div>
    <div class="marca"><img src="../../../public/img/products/logo-pc.png" alt="PC"></div>
  </div>
</section>

<section class="section-videojuegos">
<section class="section-mujer">
        <div class="container-title">
            <div class="title">
                <span class="title-line"></span>
                <h1><span class="black-text">Las mejores</span> <span class="purple-text">consolas te esperan</span></h1>
            </div>
        </div>
  <div class="product-grid">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $product): ?>
        <div class="product">
          <div class="icons">
            <i class="fas fa-heart"></i>
            <i class="fas fa-eye"></i>
          </div>
          <div class="product-image-wrapper">
            <a href="../productoDetalle.php?id=<?php echo $product['id_producto']; ?>">
              <img src="../../../public/uploads/products/<?php echo htmlspecialchars($product['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($product['nombre_producto']); ?>">
            </a>
            <button class="add-to-cart-btn">Añadir al carrito</button>
          </div>
          <p><?php echo htmlspecialchars($product['nombre_producto']); ?></p>
          <p class="price">$<?php echo number_format($product['precio'], 0, ',', '.'); ?></p>
          <p class="rating">★★★★★ (_)</p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No hay productos disponibles en esta categoría en este momento.</p>
    <?php endif; ?>
  </div>
</section>

<footer class="footer-contact">
  <div class="footer-section">
    <img src="../../../public/img/logo-positivo.png" alt="ShopNext Logo" class="footer-logo">
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