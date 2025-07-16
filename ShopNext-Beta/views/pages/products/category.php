<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}

// Obtener la categoría de la URL y evitar inyección SQL
$categoria_actual = isset($_GET['name']) ? $conexion->real_escape_string($_GET['name']) : 'Todos';

// Consulta para obtener los productos de la categoría seleccionada
$sql_productos = "SELECT id_producto, nombre_producto, precio, ruta_imagen FROM producto WHERE categoria = '{$categoria_actual}' AND stock > 0 ORDER BY id_producto DESC";
$resultado_productos = $conexion->query($sql_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/products/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title><?php echo htmlspecialchars($categoria_actual); ?> | ShopNext</title>
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
        <a href="../../public/index.php"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="../user/indexUser.php">Inicio</a>
      <a href="../auth/signUp.html">Regístrate</a>
      <a href="contact.html">Contacto</a>
      <a href="aboutUs.html">Acerca de</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button>
      <!-- Ícono de usuario -->
        <div class="user-menu-container">
          <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
          <div class="dropdown-content" id="dropdownMenu">
            <a href="../pages/account.php">Perfil</a>
            <a href="#">Pedidos 🚧</a>
            <a href="../../controllers/logout.php">Cerrar sesión</a>
          </div>
        </div>      
    </div>
  </div>
</header>
</section>

<section class="section-telefonos">
  <h2><?php echo htmlspecialchars($categoria_actual); ?></h2>
  <div class="product-grid" id="products-container">
    <?php if ($resultado_productos && $resultado_productos->num_rows > 0): ?>
        <?php while ($fila = $resultado_productos->fetch_assoc()): ?>
            <div class="product">
                <div class="product-image-wrapper">
                    <a href="../productoDetalle.php?id=<?php echo $fila['id_producto']; ?>">
                        <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($fila['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($fila['nombre_producto']); ?>">
                    </a>
                    <form class="add-to-cart-form">
                        <input type="hidden" name="id_producto" value="<?php echo $fila['id_producto']; ?>">
                        <button class="add-to-cart-btn" type="submit">Añadir al carrito</button>
                    </form>
                </div>
                <p><?php echo htmlspecialchars($fila['nombre_producto']); ?></p>
                <p class="price">$<?php echo number_format($fila['precio'], 0); ?></p>
                <p class="rating">★★★★★ (82)</p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay productos disponibles en esta categoría.</p>
    <?php endif; $conexion->close(); ?>
  </div>
</section>
<footer class="footer-contact">
  <div class="footer-section">
    <img src="../../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
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
      <li><a href="#"><img src="../../../public/img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="../../../public/img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../public/js/menuHamburguer.js"></script>
<script src="../../../public/js/cart/carrito.js"></script>

</body>
</html>