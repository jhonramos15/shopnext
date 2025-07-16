<?php
// public/index.php
session_start();
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Falló la conexión: " . $conexion->connect_error); }

// 1. Obtener la categoría de la URL. Si no se especifica, por defecto es 'Todos'.
$categoria_seleccionada = $_GET['categoria'] ?? 'Todos';

// 2. Consulta SQL base (siempre se ejecuta)
$sql_base = "SELECT p.id_producto, p.nombre_producto, p.precio, p.ruta_imagen
             FROM producto p
             WHERE p.stock > 0"; // Siempre mostramos solo productos con stock

// 3. Añadir el filtro de categoría SOLO si no es 'Todos'
$params = [];
$types = '';
if ($categoria_seleccionada !== 'Todos') {
    $sql_base .= " AND p.categoria = ?";
    $params[] = $categoria_seleccionada;
    $types .= 's';
}

$sql_base .= " ORDER BY p.id_producto DESC";

$stmt = $conexion->prepare($sql_base);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado_productos = $stmt->get_result();

$sql_mas_vendidos = "SELECT 
                        p.id_producto, 
                        p.nombre_producto, 
                        p.precio, 
                        p.ruta_imagen,
                        SUM(dp.cantidad) as total_vendido
                    FROM detalle_pedido dp
                    JOIN producto p ON dp.id_producto = p.id_producto
                    GROUP BY p.id_producto
                    ORDER BY total_vendido DESC
                    LIMIT 4";

$resultado_mas_vendidos = $conexion->query($sql_mas_vendidos);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>ShopNext | Inicio</title>
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
        <a href="index.php"><img src="img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="index.php">Inicio</a>
      <a href="../views/auth/signUp.html">Regístrate</a>
      <a href="../views/pages/contact.html">Contacto</a>
      <a href="../views/pages/aboutUs.html">Acerca de</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <a href="../views/auth/login.php"><button class="icon-btn"><i class="fa-solid fa-heart"></i></button></a>
      <a href="../views/auth/login.php"><button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
      <a href="../views/auth/login.php" class="login-btn">Iniciar Sesión</a>
    </div>
  </div>
</header>
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

<div class="contenedor-categorias">
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Ropa Femenina"><i class="fa-solid fa-person-dress"></i></a>
      <p>Ropa Femenina</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Ropa Masculina"><i class="fa-solid fa-person"></i></a>
      <p>Ropa Masculina</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Computadores"><i class="fa-solid fa-computer"></i></a>
      <p>Computadores</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Videojuegos"><i class="fas fa-gamepad"></i></a>
      <p>Videojuegos</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Deportes"><i class="fa-solid fa-baseball-bat-ball"></i></a>
      <p>Deportes</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Hogar & Belleza"><i class="fa-solid fa-star-of-life"></i></a>
      <p>Hogar & Belleza</p>
    </div>
    <div class="categoria">
      <a href="../views/pages/products/category.php?name=Celulares"><i class="fa-solid fa-mobile-button"></i></a>
      <p>Celulares</p>
    </div>
    </div>             
  </div>        
  </div>
</section>
<!-- Flash Sales (Ventas Relámpago) -->
<section>
  <div class="flash-sales">
    <div class="flash-header">
      <div class="title-container">
        <h2><span class="flash">Últimos</span> <span class="sales">Productos</span></h2>
      </div>
      <!-- Botones de Flash Sales -->
      <div class="scroll-controls">
        <button class="scroll-btn" id="scrollLeftBtn">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="scroll-btn" id="scrollRightBtn">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>

  <div class="products-container" id="products-container">
    <div class="products" id="products">
        <?php
        if ($resultado_productos && $resultado_productos->num_rows > 0) {
            while ($fila = $resultado_productos->fetch_assoc()) {
        ?>
              <div class="product">
                <div class="product-image-wrapper">
                  <a href="../views/pages/productoDetalle.php?id=<?php echo $fila['id_producto']; ?>">
                    <img src="uploads/products/<?php echo htmlspecialchars($fila['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($fila['nombre_producto']); ?>">
                  </a>
                  <form class="add-to-cart-form">
                    <input type="hidden" name="id_producto" value="<?php echo $fila['id_producto']; ?>">
                    <button type="submit" class="add-to-cart-btn">Añadir al carrito</button>
                  </form>
                </div>
                <a href="../views/pages/productoDetalle.php?id=<?php echo $fila['id_producto']; ?>" class="product-link">
                  <p class="product-title"><?php echo htmlspecialchars($fila['nombre_producto']); ?></p>
                </a>
                <p class="price">$<?php echo number_format($fila['precio'], 0); ?></p>
                <p class="rating">★★★★★ (75)</p>
              </div>
        <?php
            }
        } else {
            echo "<p>No hay productos disponibles en este momento.</p>";
        }
        $conexion->close();
        ?>
    </div> 
</div>
<section></section>

    <!-- Secciones Destacadas -->
<div class="categoria-seccion">
  <h2 class="titulo">Categorías destacadas</h2>
<div class="categoria-grid">
  <a href="../views/pages/products/phonesections.html" class="categoria-item">
    <div class="icon"><i class="fas fa-mobile-alt"></i></div>
    <p>Teléfonos</p>
  </a>
  <a href="../views/pages/products/computersections.html" class="categoria-item">
    <div class="icon"><i class="fas fa-laptop"></i></div>
    <p>Computadores</p>
  </a>
  <a href="audifonos.html" class="categoria-item">
    <div class="icon"><i class="fas fa-headphones"></i></div>
    <p>Audífonos</p>
  </a>
  <a href="videojuegos.html" class="categoria-item">
    <div class="icon"><i class="fas fa-gamepad"></i></div>
    <p>Videojuegos</p>
  </a>
</div>
</div>
<section></section>
<section class="flash-sales"> <div class="section-header">
        <div class="badge">
            <span>Este Mes</span>
        </div>
        <div class="title-container">
            <h2 class="title">Productos Más Vendidos</h2>
        </div>
        <div class="arrows-wrapper">
            <button class="arrow-btn"><i data-lucide="arrow-left"></i></button>
            <button class="arrow-btn"><i data-lucide="arrow-right"></i></button>
        </div>
    </div>

    <div class="products-container">
        <div class="products">
            <?php
            if ($resultado_mas_vendidos && $resultado_mas_vendidos->num_rows > 0) {
                while ($fila = $resultado_mas_vendidos->fetch_assoc()) {
            ?>
                    <div class="product">
                        <div class="product-image-wrapper">
                            <a href="views/pages/producto-detalle.php?id=<?php echo $fila['id_producto']; ?>">
                                <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($fila['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($fila['nombre_producto']); ?>">
                            </a>
                            <form action="/shopnext/ShopNext-Beta/controllers/carritoController.php" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="id_producto" value="<?php echo $fila['id_producto']; ?>">
                                <button type="submit" class="add-to-cart-btn">Añadir al carrito</button>
                            </form>
                        </div>
                        <p class="product-title"><?php echo htmlspecialchars($fila['nombre_producto']); ?></p>
                        <p class="price">$<?php echo number_format($fila['precio'], 0); ?></p>
                    </div>
            <?php
                }
            } else {
                echo "<p style='text-align:center; width:100%;'>Aún no hay suficientes ventas para mostrar los productos más vendidos.</p>";
            }
            ?>
        </div>
    </div>
</section>
<section></section>

<div class="contenedor-destacados">
  <div class="tarjeta" style="grid-area: ps5;">
    <img src="img/ps5-slim.jpeg" alt="PlayStation 5">
    <div class="contenido">
      <h3>PlayStation 5</h3>
      <p>Versión en blanco y negro disponible ahora.</p>
      <a href="#">Comprar Ahora</a>
    </div>
  </div>

  <div class="tarjeta" style="grid-area: coleccion;">
    <img src="img/women-pub.png" alt="Colección Femenina">
    <div class="contenido">
      <h3>Colección Femenina</h3>
      <p>Estilo exclusivo para ella.</p>
      <a href="#">Comprar Ahora</a>
    </div>
  </div>

  <div class="tarjeta" style="grid-area: altavoces;">
    <img src="img/speaker-pub.png" alt="Altavoces">
    <div class="contenido">
      <h3>Altavoces</h3>
      <p>Altavoces inalámbricos Amazon.</p>
      <a href="#">Comprar Ahora</a>
    </div>
  </div>

  <div class="tarjeta" style="grid-area: perfume;">
    <img src="img/gucci-pub.png" alt="Perfume">
    <div class="contenido">
      <h3>Perfume</h3>
      <p>GUCCI INTENSE OUD EDP.</p>
      <a href="#">Comprar Ahora</a>
    </div>
  </div>
</div>
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
<script src="js/alertas.js"></script> 
<script src="js/cart/carrito.js"></script>
<script src="js/index.js"></script>
<script src="js/menuHamburguer.js"></script>
</body>
</html>