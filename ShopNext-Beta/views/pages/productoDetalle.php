<?php
// public/producto-detalle.php
session_start();

// 1. Obtener el ID del producto desde la URL. Si no hay ID, detenemos.
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_producto === 0) {
    // Puedes redirigir a una página 404 si quieres
    die("Error: Producto no especificado.");
}

// 2. Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}

// 3. Consulta para obtener los detalles del producto y el nombre del vendedor
$sql_producto = "SELECT 
                    p.*, 
                    v.nombre AS nombre_vendedor
                 FROM producto p
                 JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                 WHERE p.id_producto = ?";

$stmt = $conexion->prepare($sql_producto);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Este producto no existe o no está disponible.");
}
// Guardamos los datos del producto en una variable
$producto = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/products/productoDetalle.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title><?php echo htmlspecialchars($producto['nombre_producto']); ?> | ShopNext</title>
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
                <a href="../../public/index.php"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
            </div>
            <!-- Menú Hamburguesa -->
            <button class="hamburger" onclick="toggleMenu()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Nav Menú -->
        <nav class="nav-links" id="navMenu">
            <a href="../../public/index.php">Inicio</a>
            <a href="../auth/signUp.html">Regístrate</a>
            <a href="contact.html">Contacto</a>
            <a href="aboutUs.html">Acerca de</a>
        </nav>

        <!-- Buscador -->
        <div class="icons">
            <div class="buscador">
                <input type="text" placeholder="¿Qué estás buscando?">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <!-- Favoritos -->
            <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
            <!-- Carrito -->
            <button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button>
            <!-- Iniciar Sesión -->
            <a href="../auth/login.php" class="login-btn">Iniciar Sesión</a>
        </div>
    </div>
</header>
    <main>
        <section class="product-view">
            <div class="image-gallery">
                <div class="thumbnails">
                    <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" class="thumbnail-img active">
                </div>
                <div class="main-image-container">
                    <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="main-image">
                </div>
            </div>

            <div class="product-details">
                <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>
                    <span>(150 Reseñas)</span>
                    <?php if ($producto['stock'] > 0): ?>
                        <span class="stock-status" style="color: #00B517;">| En Stock</span>
                    <?php else: ?>
                        <span class="stock-status" style="color: #DB4444;">| Agotado</span>
                    <?php endif; ?>
                </div>
                <p class="product-price">$<?php echo number_format($producto['precio'], 0); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                <div class="actions">
                    <div class="quantity-selector">
                        <button class="quantity-btn" id="decrease-qty">-</button>
                        <input type="text" class="quantity-input" id="quantity" value="1" readonly>
                        <button class="quantity-btn" id="increase-qty">+</button>
                    </div>
                    <a href="../user/cart/carrito.php?producto_id=<?php echo $producto['id_producto']; ?>" class="buy-btn">Comprar Ahora</a>
                </div>
                <div class="delivery-info">
                    <div class="delivery-option">
                        <i class="fas fa-truck"></i>
                        <div>
                           <h4>Envío Gratuito</h4>
                           <p>Ingresa tu código postal para ver disponibilidad</p>
                        </div>
                    </div>
                    <div class="delivery-option">
                        <i class="fas fa-undo"></i>
                        <div>
                           <h4>Devolución Gratuita</h4>
                           <p>Devoluciones gratis durante 30 días. Ver detalles</p>
                        </div>
                    </div>
                </div>
                
                <div class="delivery-info">
                    </div>
            </div>
        </section>
    </main>
<footer class="footer-contact">
  <div class="footer-section">
    <img src="../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
  </div>
  <div class="footer-section">
    <h3>Información</h3>
    <ul>
      <li><a href="aboutUs.html">Acerca de</a></li>
      <li><a href="contact.html">Contacto</a></li>
      <li><a href="../auth/signUp.html">Regístrate</a></li>
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
      <li><a href="#"><img src="../../public/img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="../../public/img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="../../public/img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>
</body>
</html>