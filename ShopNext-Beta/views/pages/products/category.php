<?php
// Incluimos la conexión a la base de datos.
require_once __DIR__ . '/../../../config/conexion.php';

class ProductController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtiene los productos filtrando por el nombre de la categoría.
     */
    public function getProductsByCategory($categoryName) {
        $query = "SELECT 
                    id_producto, 
                    nombre_producto, 
                    descripcion, 
                    precio, 
                    stock, 
                    ruta_imagen 
                  FROM 
                    producto
                  WHERE 
                    categoria = :category_name AND stock > 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_name', $categoryName);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene TODOS los productos de la base de datos.
     */
    public function getAllProducts() {
        // La consulta ahora no tiene un WHERE para categoría, así que trae todo.
        $query = "SELECT 
                    id_producto, 
                    nombre_producto, 
                    descripcion, 
                    precio, 
                    stock, 
                    ruta_imagen 
                  FROM 
                    producto
                  WHERE 
                    stock > 0"; // Opcional: para no mostrar productos agotados.
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// 2. Creamos una instancia del controlador y obtenemos TODOS los productos
$productController = new ProductController();
$products = $productController->getAllProducts(); // <- Esta es la función clave

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/products/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>Todos los Productos | ShopNext</title>
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

<main class="main-content">
    <section class="section-productos">
      <h2>Nuestro Catálogo Completo</h2>
      <p class="subtitulo-catalogo">Explora todos los productos que tenemos para ti en un solo lugar.</p>
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
          <p>No hay productos disponibles en este momento.</p>
        <?php endif; ?>
      </div>
    </section>
</main>

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