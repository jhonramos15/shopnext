<?php
// views/user/cart/carrito.php
session_start();
// Guardian de rutas
require_once __DIR__ . '/../../../controllers/authGuardCliente.php'; 

// Conexión a la BD
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtenemos el id_cliente del usuario
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$cliente_res = $stmt_cliente->get_result();
$id_cliente = ($cliente_res->num_rows > 0) ? $cliente_res->fetch_assoc()['id_cliente'] : 0;

// Obtenemos el id_carrito de ese cliente
$id_carrito = 0;
if ($id_cliente > 0) {
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $carrito_res = $stmt_carrito->get_result();
    $id_carrito = ($carrito_res->num_rows > 0) ? $carrito_res->fetch_assoc()['id_carrito'] : 0;
}

// Obtenemos todos los productos de ESE carrito
$items_del_carrito = [];
if ($id_carrito > 0) {
    $sql_items = "SELECT p.nombre_producto, p.precio, p.ruta_imagen, pc.cantidad, pc.id_producto_carrito, p.id_producto
                  FROM producto_carrito pc
                  JOIN producto p ON pc.id_producto = p.id_producto
                  WHERE pc.id_carrito = ?";
    $stmt_items = $conexion->prepare($sql_items);
    $stmt_items->bind_param("i", $id_carrito);
    $stmt_items->execute();
    $resultado_items = $stmt_items->get_result();
    $items_del_carrito = $resultado_items->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Carrito de Compras | ShopNext</title>
    <link rel="stylesheet" href="../../../public/css/carrito.css"> 
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
        <a href="../indexUser.php"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="#">Productos</a>
      <a href="../pages/contact.php">Contacto</a>
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
            <a href="../../pages/account.php">Perfil</a>
            <a href="../../user/pages/pedidos.php">Pedidos</a>
            <a href="../../../controllers/logout.php">Cerrar sesión</a>
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
                    <div class="product-image">
                        <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($item['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                        <div class="delete-product">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
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
            </div>
        <?php
            }
        } else {
            echo "<p class='empty-cart'>Tu carrito está vacío.</p>";
        }
        ?>
    </div>

    <div class="cart-bottom">
        <div class="coupon-section">
            <input type="text" placeholder="Código de Cupón" />
            <button class="btn btn-primary">Aplicar Cupón</button>
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
    <img src="../../../public/img/logo-positivo.png" alt="ShopNext Logo" class="footer-logo">
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
      <li><a href="#"><img src="../../../public/img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="../../../public/img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../public/js/alertas.js"></script>
<script src="../../../public/js/cart/carrito.js"></script>
<script src="../../../public/js/dropdown.js"></script>

</body>
</html>
