<?php
// Validamos si el token viene por la URL
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambio Contraseña | ShopNext</title>
  <link rel="icon" href="../img/icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../../public/css/recovery.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    
  <!-- Primera Cabecera del Sitio Web -->
  <header>
    <div id="header-black">
      <p>Rebajas de Verano en Todos los Trajes de Baño y Envío Exprés Gratuito: ¡50 % de Descuento!</p>
      <h2>¡Compra ahora!</h2>
      <select id="languages">
        <option value="Español:">Español:</option>
        <option value="English">English</option>
      </select>
    </div>

    <!-- Segunda Cabecera del Sitio Web -->
    <div id="header-principal">
      <a href="../../public/index.html">
        <img src="../../public/img/logo.png" alt="Logo ShopNexs">
      </a>

      <div id="nav">
        <a href="../../public/index.html">Inicio</a>
        <a href="signUp.html">Regístrate</a>
        <a href="../pages/aboutUs.html">Acerca de</a>
        <a href="../pages/contact.html">Contacto</a>
      </div>

      <!-- Buscador -->
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button type="submit">
          <i class="fa-solid fa-magnifying-glass" style="color: #121212;"></i>
        </button>
      </div>

      <!-- Botón de Corazón -->
      <div class="heart">
        <button type="submit">
          <i class="fa-solid fa-heart" style="color: #121212;"></i>
        </button>
      </div>

      <!-- Botón de Carrito -->
      <div class="cart">
        <button type="submit">
          <i class="fa-solid fa-cart-shopping" style="color: #121212;"></i>
        </button>
      </div>
    </div>
  </header>
  <section></section>
  <div id="h2-principal"><h2>¡Nos alegra verte de nuevo!</h2></div>

  <!-- Formulario recuperar contraseña -->
  <form id="form-password" method="POST" action="../../controllers/updatePassword.php">
    <!-- Token oculto -->
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    <input type="password" placeholder="Tu nueva contraseña" id="clave" name="clave" required>

    <div class="password-button">
      <button type="submit">Enviar</button>
    </div>
  </form>

  <footer>
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
</body>
</html>
