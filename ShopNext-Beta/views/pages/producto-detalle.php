<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shop/products/producto-detalle.css"> 
    <link rel="stylesheet" href="css/base.css"> 
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
                <a href="index.php"><img src="img/logo.svg" alt="ShopNext"></a>
            </div>
            <!-- Menú Hamburguesa -->
            <button class="hamburger" onclick="toggleMenu()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Nav Menú -->
    <nav class="nav-links">
        <a href="/shopnext/ShopNext-Beta/public/index.php">Inicio</a>

        <?php if ($data['usuario_logueado']): ?>
            <a href="?action=products">Productos</a>
            <a href="?action=contact">Contacto</a>
        <?php else: ?>
            <a href="?action=signup">Regístrate</a>
            <a href="index.php?action=contact">Contacto</a>
            <a href="?action=about">Acerca de</a>
        <?php endif; ?>
    </nav>

        <!-- Buscador -->
        <div class="icons">
            <div class="buscador">
                <input type="text" placeholder="¿Qué estás buscando?">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
      <!-- Favoritos y Carrito, logueados -->
      <?php if ($data['usuario_logueado']): ?>
        <a href="?action=favorites" title="Favoritos"><i class="fa-solid fa-heart"></i></a>
        <a href="/shopnext/ShopNext-Beta/views/user/cart/carrito.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
        <!-- Ícono de usuario, solo si está logueado -->
        <div class="user-menu-container">
            <i class="fas fa-user user-icon"></i>
            
            <div class="dropdown-content" id="dropdownMenu">
              <a href="?action=account">
                <i class="fas fa-user-circle"></i> <span>Mi Perfil</span>
              </a>
              <a href="/shopnext/ShopNext-Beta/views/user/pages/pedidos.php">
                <i class="fas fa-box"></i> <span>Mis Pedidos</span>
              </a>
              <hr>
              <a href="index.php?action=logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
              </a>
            </div>
        </div>      
        <!-- Favoritos y Carrito, no logueados -->
      <?php else: ?>
        <a href="/shopnext/ShopNext-Beta/views/auth/login.php" title="Favoritos"><i class="fa-solid fa-heart"></i></a>
        <a href="/shopnext/ShopNext-Beta/views/auth/login.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="?action=login" class="login-btn">Iniciar Sesión</a>
      <?php endif; ?>
        </div>
    
    </div>
</header>
<main class="product-detail-container">
    <div class="product-images">
        <div class="main-image">
            <!-- Imagen principal del producto -->
            <img src="<?php echo BASE_URL . 'uploads/products/' . htmlspecialchars($data['producto']['imagenes'][0]['ruta_imagen'] ?? 'default.jpg'); ?>" alt="Imagen principal del producto" id="mainProductImage">
        </div>
        <div class="thumbnail-images">
            <!-- CORRECCIÓN: Las miniaturas solo se muestran si hay MÁS de una imagen, para evitar duplicados visuales. -->
            <?php if (count($data['producto']['imagenes']) > 1): ?>
                <?php foreach ($data['producto']['imagenes'] as $imagen): ?>
                    <img src="<?php echo BASE_URL . 'uploads/products/' . htmlspecialchars($imagen['ruta_imagen']); ?>" alt="Miniatura del producto" class="thumbnail">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-info">
        <h1><?php echo htmlspecialchars($data['producto']['nombre_producto']); ?></h1>
        
        <div class="reviews-summary">
            <!-- Corregido: Mostrar estrellas basadas en el promedio -->
            <div class="stars">
                <?php 
                $promedioRedondeado = round($data['producto']['resena_promedio']);
                for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-solid fa-star <?php echo ($i <= $promedioRedondeado) ? 'filled' : ''; ?>"></i>
                <?php endfor; ?>
            </div>
            <span>(<?php echo $data['producto']['resena_total']; ?> Reseñas)</span> |
            <span style="color: <?php echo $data['producto']['stock'] > 0 ? '#00A082' : '#DB4444'; ?>;">
                <?php echo $data['producto']['stock'] > 0 ? 'En Stock' : 'Agotado'; ?>
            </span>
        </div>

        <p class="price">$<?php echo htmlspecialchars(number_format($data['producto']['precio'], 0, ',', '.')); ?></p>
        <p class="description"><?php echo nl2br(htmlspecialchars($data['producto']['descripcion'])); ?></p>
        
        <hr>
        
<form id="form-add-to-cart" method="POST">
    <input type="hidden" name="id_producto" value="<?php echo $data['producto']['id_producto']; ?>">
    
    <div class="purchase-actions">
        <div class="quantity-selector">
            <button type="button" class="quantity-btn" id="decrease-qty">-</button>
            <input type="text" name="cantidad" id="quantity-input" value="1" min="1" readonly>
            <button type="button" class="quantity-btn" id="increase-qty">+</button>
        </div>

        <button type="submit" class="buy-now-btn">Añadir al Carrito</button>
        
        <button type="button" id="add-to-favorites-btn" class="add-to-favorites-btn" data-id-producto="<?php echo $data['producto']['id_producto']; ?>">
            <i class="fa-regular fa-heart"></i>
        </button>
    </div>
    <div class="delivery-info-container">
    <div class="delivery-option">
        <div class="delivery-icon">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div class="delivery-text">
            <h4>Envío Gratis</h4>
            <p>Ingresa tu código postal para ver la disponibilidad.</p>
        </div>
    </div>
    <div class="delivery-option">
        <div class="delivery-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <div class="delivery-text">
            <h4>Política de Devolución</h4>
            <p>Devoluciones gratis durante 30 días. Ver detalles.</p>
        </div>
    </div>
</div>
</form>
    </div>
</main>
    
<!-- ================================================== -->
<!-- SECCIÓN DE RESEÑAS (FORMULARIO Y LISTA)            -->
<!-- ================================================== -->
<div class="reviews-container">
    <h2>Opiniones de los Clientes</h2>

    <!-- FORMULARIO PARA AÑADIR UNA NUEVA RESEÑA -->
    <div class="review-form-section">
        <h3>Deja tu opinión</h3>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <form id="form-resena" method="POST">
                <!-- Campo oculto con el ID del producto -->
                <input type="hidden" name="id_producto" value="<?php echo $data['producto']['id_producto']; ?>">
                
                <div class="form-group">
                    <label>Tu calificación:</label>
                    <div class="star-rating">
                        <!-- CORRECCIÓN: Se añade el ícono de la estrella dentro de cada label -->
                        <input type="radio" id="star5" name="puntuacion" value="5" required><label for="star5" title="5 estrellas"><i class="fa-solid fa-star"></i></label>
                        <input type="radio" id="star4" name="puntuacion" value="4"><label for="star4" title="4 estrellas"><i class="fa-solid fa-star"></i></label>
                        <input type="radio" id="star3" name="puntuacion" value="3"><label for="star3" title="3 estrellas"><i class="fa-solid fa-star"></i></label>
                        <input type="radio" id="star2" name="puntuacion" value="2"><label for="star2" title="2 estrellas"><i class="fa-solid fa-star"></i></label>
                        <input type="radio" id="star1" name="puntuacion" value="1"><label for="star1" title="1 estrella"><i class="fa-solid fa-star"></i></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comentario">Tu comentario:</label>
                    <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe aquí tu opinión sobre el producto..."></textarea>
                </div>

                <button type="submit" class="submit-review-btn">Enviar Reseña</button>
            </form>
        <?php else: ?>
            <!-- Mensaje para usuarios que no han iniciado sesión -->
            <p>Debes <a href="<?php echo BASE_URL; ?>login">iniciar sesión</a> para poder dejar una reseña.</p>
        <?php endif; ?>
    </div>

    <!-- LISTA DE RESEÑAS EXISTENTES -->
    <?php if (!empty($data['producto']['reseñas'])): ?>
    <div class="reviews-list">
        <h3>Comentarios Recientes</h3>
        <?php foreach($data['producto']['reseñas'] as $reseña): ?>
            <div class="review-card">
                <p class="review-author"><strong><?php echo htmlspecialchars($reseña['nombre_usuario']); ?></strong></p>
                <div class="review-rating">
                    <!-- Corregido: Mostrar la puntuación de esta reseña específica -->
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fa-solid fa-star <?php echo ($i <= $reseña['puntuacion']) ? 'filled' : ''; ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="review-comment"><?php echo htmlspecialchars($reseña['comentario']); ?></p>
                <!-- Corregido: El campo se llama fecha_creacion -->
                <small class="review-date"><?php echo date('d M, Y', strtotime($reseña['fecha_creacion'])); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p>Este producto aún no tiene reseñas. ¡Sé el primero en opinar!</p>
    <?php endif; ?>
</div>
<footer class="footer-contact">
  <div class="footer-section">
    <img src="img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
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
      <li><a href="#"><img src="img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const App = {
        baseUrl: '<?php echo BASE_URL; ?>'
    };

    // Tu script para la galería de imágenes también puede ir aquí.
    document.addEventListener('DOMContentLoaded', () => {
        const mainImage = document.getElementById('mainProductImage');
        const thumbnails = document.querySelectorAll('.thumbnail');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.src;
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>

<script src="<?php echo BASE_URL; ?>js/shop/product-details.js"></script>
<script src="<?php echo BASE_URL; ?>js/user/dropdown.js"></script>

</body>
</html>