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
  <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">  
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
    <div class="logo-menu">
      <div class="logo">
        <a href="../../public/index.php"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <nav class="nav-links" id="navMenu">
      <a href="../../public/index.php">Inicio</a>
      <a href="../auth/signUp.html">Regístrate</a>
      <a href="../pages/contact.html">Contacto</a>
      <a href="../pages/aboutUs.html">Acerca de</a>
    </nav>

    <div class="icons">
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
<section class="main-section"></section>
<!-- CONTENIDO CORREGIDO -->
<div class="forgot">
  <section class="no-border">
    <h2>¡Nos alegra verte de nuevo!</h2>

    <form id="form-password" method="POST" action="../../controllers/updatePassword.php">
      <!-- Token oculto -->
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

      <input type="password" placeholder="Tu nueva contraseña" id="clave" name="clave" required>

      <div class="password-button">
        <button type="submit">Enviar</button>
      </div>
    </form>
  </section>
</div>

<!-- Footer -->
<footer class="footer-contact">
  <div class="footer-section">
    <img src="../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
  </div>
  <div class="footer-section">
    <h3>Información</h3>
    <ul>
      <li><a href="../pages/aboutUs.html">Acerca de</a></li>
      <li><a href="../pages/contact.html">Contacto</a></li>
      <li><a href="signUp.html">Regístrate</a></li>
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

<script src="../../public/js/menuHamburguer.js"></script>
</body>
</html>
