<?php
// 1. Incluimos el controlador de productos
require_once __DIR__ . '/../../../controllers/product/productController.php';

// 2. Creamos una instancia del controlador y obtenemos los productos de la categoría "Deportes"
$productController = new ProductController();
$products = $productController->getProductsByCategory('Deportes');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/products/stylessports.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>Deportes | ShopNext</title>
</head>
<body>
<header>
  <div class="header-top">
    <p>Rebajas de Verano: ¡50 % de Descuento!</p>
    <h2>¡Compra Ahora!</h2>
    <select>
      <option value="es">Español</option>
      <option value="en">English</option>
    </select>
  </div>

  <div class="header-main">
    <div class="logo-menu">
      <div class="logo">
        <a href="../../../public/index.html"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <nav class="nav-links" id="navMenu">
      <a href="../../../public/index.html">Inicio</a>
      <a href="../../auth/signUp.html">Regístrate</a>
      <a href="../contact.html">Contacto</a>
      <a href="../aboutUs.html">Acerca de</a>
    </nav>

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
<section class="seccion-deportes">
  <div class="contenido-deportes">
    <h2>SUPERA TUS LÍMITES CON EL MEJOR EQUIPO</h2>
  </div>
</section>

<section class="seccion-marcas">
  <h2>LAS MEJORES MARCAS DEPORTIVAS</h2>
  <div class="contenedor-marcas">
    <div class="marca"><img src="../../../public/img/products/Nike Logo PNG (1).png " alt="Nike"></div>
    <div class="marca"><img src="../../../public/img/products/Adidas t-shirt logo.png" alt="Adidas"></div>
    <div class="marca"><img src="../../../public/img/products/descarga.png" alt="Puma"></div>
    <div class="marca"><img src="../../../public/img/products/arnold.png" alt="Under Armour"></div>
    <div class="marca"><img src="../../../public/img/products/Reebok - Logo.png" alt="Reebok"></div>
    <div class="marca"><img src="../../../public/img/products/Wilson Logotype.png" alt="Wilson"></div>
    <div class="marca"><img src="../../../public/img/products/Charm_medusa🐊.png" alt="Fila"></div>
  </div>
</section>
<?php
// 1. Incluimos el controlador de productos
require_once __DIR__ . '/../../../controllers/product/productController.php';

// 2. Creamos una instancia del controlador y obtenemos los productos de la categoría "Deportes"
$productController = new ProductController();
$products = $productController->getProductsByCategory('Deportes');
?>
<section class="section-deportes">
  <h2>Equipamiento y Ropa Deportiva</h2>
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