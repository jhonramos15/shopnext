<?php
session_start();

// 1. Obtener el ID del producto de la URL.
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_producto === 0) {
    die("Error: Producto no especificado.");
}

// 2. Abrir la conexión a la base de datos (UNA SOLA VEZ).
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

// 3. CONSULTA 1: Obtener detalles del producto y vendedor.
$sql_producto = "SELECT p.*, v.nombre AS nombre_vendedor
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
$producto = $resultado->fetch_assoc();
$stmt->close();

// --- OBTENER RESEÑAS Y ESTADÍSTICAS ---
$id_producto_actual = $producto['id_producto'];

// 1. Obtener todas las reseñas.
$sql_reseñas = "SELECT nombre_usuario, puntuacion, comentario, DATE_FORMAT(fecha_creacion, '%d/%m/%Y') AS fecha_formateada 
                FROM resenas WHERE id_producto = ? ORDER BY fecha_creacion DESC";
$stmt_reseñas = $conexion->prepare($sql_reseñas);
$stmt_reseñas->bind_param("i", $id_producto_actual);
$stmt_reseñas->execute();
$reseñas = $stmt_reseñas->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_reseñas->close();

// 2. Obtener estadísticas de puntuación.
$puntuaciones = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$total_reseñas = 0;
$suma_puntuaciones = 0;

$sql_stats = "SELECT puntuacion, COUNT(id_resena) as conteo FROM resenas WHERE id_producto = ? GROUP BY puntuacion";
$stmt_stats = $conexion->prepare($sql_stats);
$stmt_stats->bind_param("i", $id_producto_actual);
$stmt_stats->execute();
$resultado_stats = $stmt_stats->get_result();

while ($fila = $resultado_stats->fetch_assoc()) {
    $puntuaciones[$fila['puntuacion']] = $fila['conteo'];
    $total_reseñas += $fila['conteo'];
    $suma_puntuaciones += $fila['puntuacion'] * $fila['conteo'];
}
$stmt_stats->close();

// --- VERIFICAR SI EL PRODUCTO ES FAVORITO DEL USUARIO ---
$es_favorito = false; // Valor por defecto
if (isset($_SESSION['id_usuario'])) {
    // Necesitas una conexión a la BD aquí si la cerraste antes
    // $conexion = new mysqli(...); 
    
    // Obtener id_cliente desde id_usuario
    $id_usuario = $_SESSION['id_usuario'];
    $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
    $stmt_cliente->bind_param("i", $id_usuario);
    $stmt_cliente->execute();
    $resultado_cliente = $stmt_cliente->get_result();
    
    if ($resultado_cliente->num_rows > 0) {
        $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];

        // Comprobar si existe en la tabla de favoritos
        $stmt_check = $conexion->prepare("SELECT id_favorito FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?");
        $stmt_check->bind_param("ii", $id_cliente, $id_producto);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $es_favorito = true;
        }
        $stmt_check->close();
    }
    $stmt_cliente->close();
}

// 3. Calcular el promedio.
$puntuacion_promedio = ($total_reseñas > 0) ? round($suma_puntuaciones / $total_reseñas, 1) : 0;
// 6. ¡AHORA SÍ! Cerramos la conexión al final de todo el PHP.
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
            <a href="../user/pages/favoritos.php"><button class="icon-btn"><i class="fa-solid fa-heart"></i></button></a>
            <!-- Carrito -->
            <button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button>
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
                        <?php
                            // Redondea el promedio para mostrar las estrellas
                            $rating_redondeado = round($puntuacion_promedio);

                            // Dibuja las estrellas (llenas y vacías)
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating_redondeado) {
                                    echo '<i class="fas fa-star"></i>'; // Estrella llena
                                } else {
                                    echo '<i class="far fa-star"></i>'; // Estrella vacía
                                }
                            }
                        ?>
                    </div>
                    <span>(<?php echo $total_reseñas; ?> Reseñas)</span>
                        
                    <?php if ($producto['stock'] > 0): ?>
                        <span class="stock-status">| En Stock</span>
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
            <button class="wishlist-btn <?php if ($es_favorito) echo 'active'; ?>" 
            onclick="toggleFavorito(<?php echo $producto['id_producto']; ?>, this)">
        <i class="fa-heart <?php echo $es_favorito ? 'fas' : 'far'; ?>"></i>
    </button>
    </div>

    <?php if (isset($_SESSION['id_usuario'])): ?>
        <a href="javascript:void(0);" onclick="agregarAlCarrito(<?php echo $producto['id_producto']; ?>)" class="buy-btn">Comprar Ahora</a>
    <?php else: ?>
        <a href="javascript:void(0);" onclick="mostrarAlertaLogin()" class="buy-btn">Comprar Ahora</a>
    <?php endif; ?>
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

    <section class="reviews-section">
        <hr>
        <h3>Valoraciones del Producto</h3>

        <?php if ($total_reseñas > 0): ?>
            <div class="rating-summary">
                <div class="rating-bars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="rating-bar-item">
                            <span><?php echo $i; ?></span>
                            <i class="fas fa-star"></i>
                            <div class="bar-container">
                                <div class="bar" style="width: <?php echo ($puntuaciones[$i] / $total_reseñas) * 100; ?>%;"></div>
                            </div>
                            <span class="count"><?php echo $puntuaciones[$i]; ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="rating-average">
                    <div class="average-score"><?php echo number_format($puntuacion_promedio, 1); ?></div>
                    <div class="average-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-star <?php echo ($i <= $puntuacion_promedio) ? 'fas' : 'far'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="total-reviews"><?php echo $total_reseñas; ?> valoraciones</div>
                </div>
            </div>
        <?php endif; ?>

        <div class="reviews-content-wrapper">
            <div class="review-form-container">
    <h3 class="review-form-title">Escribe tu propia reseña</h3>

    <?php 
    // Verificamos si la variable de sesión del usuario existe
    if (isset($_SESSION['id_usuario'])): 
    ?>

        <form id="review-form">
            <input type="hidden" name="id_producto" value="<?php echo $id_producto_actual; ?>">
            
            <div class="form-group rating-group">
                <div class="form-group">
                    <label>Puntuación:</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="puntuacion" value="5" required><label for="star5" title="5 estrellas">★</label>
                        <input type="radio" id="star4" name="puntuacion" value="4"><label for="star4" title="4 estrellas">★</label>
                        <input type="radio" id="star3" name="puntuacion" value="3"><label for="star3" title="3 estrellas">★</label>
                        <input type="radio" id="star2" name="puntuacion" value="2"><label for="star2" title="2 estrellas">★</label>
                        <input type="radio" id="star1" name="puntuacion" value="1"><label for="star1" title="1 estrella">★</label>
                    </div>
                </div>

            <div class="form-group">
                <label for="comentario">Tu Reseña:</label>
                <textarea id="comentario" name="comentario" rows="4" placeholder="Cuéntanos qué te pareció el producto..."></textarea>
            </div>

            <button type="submit" class="submit-review-btn">Enviar Reseña</button>
        </form>

    <?php else: ?>

        <div class="login-prompt">
            <p>Debes iniciar sesión para poder dejar una reseña.</p>
            <a href="/shopnext/ShopNext-Beta/views/auth/login.php" class="login-link-btn">Iniciar Sesión</a>
        </div>

    <?php endif; ?>

</div>

            <div class="reviews-list">
                <h4>Comentarios de otros clientes</h4>
                <?php if (empty($reseñas)): ?>
                    <p>Este producto aún no tiene valoraciones. ¡Sé el primero!</p>
                <?php else: ?>
                    <?php foreach ($reseñas as $resena): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <strong><?php echo htmlspecialchars($resena['nombre_usuario']); ?></strong>
                                <span class="review-date"><?php echo $resena['fecha_formateada']; ?></span>
                            </div>
                            <div class="review-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo ($i <= $resena['puntuacion']) ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../public/js/reviews.js"></script>
<script src="../../public/js/alertas.js"></script>
<script src="../../public/js/cart/carrito.js"></script>
<script src="/shopnext/ShopNext-Beta/public/js/user/favoritos.js"></script>
</body>
</html>