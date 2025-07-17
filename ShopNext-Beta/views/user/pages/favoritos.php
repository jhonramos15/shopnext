<?php
session_start();
// Guardián para asegurar que el usuario sea un cliente logueado
require_once __DIR__ . '/../../../controllers/authGuardCliente.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtención del id_cliente a partir del id_usuario de la sesión
$id_cliente = null;
if (isset($_SESSION['id_usuario'])) {
    $id_usuario_session = $_SESSION['id_usuario'];
    $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
    $stmt_cliente->bind_param("i", $id_usuario_session);
    $stmt_cliente->execute();
    $resultado_cliente = $stmt_cliente->get_result();
    if ($resultado_cliente->num_rows > 0) {
        $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
    }
    $stmt_cliente->close();
}

// Obtener los productos favoritos de ese cliente
$productos_favoritos = [];
if ($id_cliente !== null) {
    $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.ruta_imagen
            FROM producto p
            JOIN lista_favoritos lf ON p.id_producto = lf.id_producto
            WHERE lf.id_cliente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $resultado_favoritos = $stmt->get_result();
    $productos_favoritos = $resultado_favoritos->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
$conexion->close();
?>

<link rel="stylesheet" href="/shopnext/ShopNext-Beta/public/css/favoritos.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
        <a href="../indexUser.php"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="../indexUser.php">Inicio</a>
      <a href="pedidos.php">Pedidos</a>
    </nav>

    <!-- Buscador -->
    <div class="icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <!-- Favoritos -->
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <!-- Ícono de usuario -->
        <div class="user-menu-container">
          <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
          <div class="dropdown-content" id="dropdownMenu">
            <a href="../pages/account.php">Perfil</a>
            <a href="pedidos.php">Pedidos</a>
            <a href="../../../controllers/logout.php">Cerrar sesión</a>
          </div>
        </div>   
    </div>
  </div>
</header>

<main class="wishlist-container">
    <div class="wishlist-header">
        <h1>Mi Lista de Deseos (<?php echo count($productos_favoritos); ?>)</h1>
        <button class="move-all-btn">Mover todo al Carrito</button>
    </div>

    <?php if (count($productos_favoritos) > 0): ?>
        <div class="wishlist-grid">
            <?php foreach ($productos_favoritos as $producto): ?>
                <div class="product-card" data-product-id="<?php echo $producto['id_producto']; ?>">
                    <div class="product-image-wrapper">
                        <button class="delete-btn" title="Eliminar de favoritos">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        
                        <a href="../pages/productoDetalle.php?id=<?php echo $producto['id_producto']; ?>">
                            <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($producto['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                        </a>

                        <button class="add-to-cart-btn">
                            <i class="fas fa-shopping-cart"></i> Añadir al Carrito
                        </button>
                    </div>
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                        <p class="product-price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span class="review-count">(65)</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-wishlist">
            <p>Tu lista de deseos está vacía. ¡Explora nuestros productos y añade tus favoritos!</p>
        </div>
    <?php endif; ?>

</main>

<!-- Footer -->
     <footer class="footer-contact">
      <div class="footer-section">
          <img src="../img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
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
              <img src="../img/Icon-Twitter.png" alt="Icon Twitter">
              <img src="../img/icon-instagram.png" alt="Icon Instagram">
              <img src="../img/Icon-Linkedin.png" alt="Icon LinkedIn">
            </ul>
          </div>
    </footer>