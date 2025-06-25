<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: ../dashboard/adminView.php");
        exit;
    } elseif ($_SESSION['rol'] === 'cliente') {
        header("Location: ../user/indexUser.php");
        exit;
    } elseif ($_SESSION['rol'] === 'vendedor') {
        header("Location: ../dashboard/vendedorView.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ShopNext</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/newLogin.css">
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
        <a href="../../public/index.html"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="index.html">Inicio</a>
      <a href="signUp.html">Regístrate</a>
      <a href="../views/pages/contact.html">Contacto</a>
      <a href="../views/pages/aboutUs.html">Acerca de</a>
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
      <a href="../views/auth/login.html" class="login-btn">Iniciar Sesión</a>
    </div>
  </div>
</header>

<section class="main-section">
  <div class="sign-up">

    <!-- Imagen izquierda -->
    <div class="img-sign-up">
      <img src="../../public/img/foto-login.png" alt="Login">
    </div>

    <!-- Formulario a la derecha -->
    <div class="form-sign-up">
      <h2>Iniciar Sesión</h2>
      <h3>Ingresa los detalles abajo</h3>

      <form method="POST" action="../../controllers/procesoLogin.php">
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" name="correo" placeholder="Email" required>
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="Contraseña" required>
        </div>

        <div class="buttoncreate">
          <button type="submit">Iniciar Sesión</button>
        </div>

        <div class="a-login">
          <a href="forgotPassword.php">¿Olvidaste la Contraseña?</a>
        </div>
      </form>
    </div>

  </div>
</section>
    <!-- Footer -->
    <footer>
      <div class="footer-section">
        <img src="../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
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
          <img src="../../public/img/Icon-Twitter.png" alt="Icon Twitter">
          <img src="../../public/img/icon-instagram.png" alt="Icon Instagram">
          <img src="../../public/img/Icon-Linkedin.png" alt="Icon LinkedIn">
        </ul>
      </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../public/js/login.js"></script>
<script src="../../public/js/alertas.js"></script>
<script src="../../public/js/menuHamburguer.js"></script>
</body>
</html>