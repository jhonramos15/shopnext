<?php
session_start();

// Si ya está logueado, redirigir
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
} if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'sesion_expirada'): ?>
    <div class="alert">Tu sesión ha expirado por inactividad. Por favor, inicia sesión de nuevo.</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ShopNext</title>
    <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

  <!-- Alerta Descuento -->
  <header>
    <div id="header-black">
      <p>Rebajas de Verano en Todos los Trajes de Baño y Envío Exprés Gratuito: ¡50 % de Descuento!</p>
      <h2>¡Compra Ahora!</h2>
      <select id="languages">
        <option value="Español:">Español:</option>
        <option value="English">English</option>
      </select>
    </div>

      <!-- Header Principal -->
      <div id="header-principal">
        <a href="../../public/index.html">
          <img src="../../public/img/logo.svg" alt="Logo ShopNext">
        </a>
        <div id="nav">
          <a href="../../public/index.html">Inicio</a>
          <a href="signUp.html">Regístrate</a>
          <a href="../pages/aboutUs.html">Acerca de</a>
          <a href="../pages/contact.html">Contacto</a>
        </div>

        <!-- Contenedor de la barra de búsqueda -->
        <div class="buscador">
          <input type="text" placeholder="¿Qué estás buscando?">
          <button type="submit">
          <i class="fa-solid fa-magnifying-glass" style="color: #121212;"></i>
          </button>
        </div>

        <!-- Botón de Corazón-->
        <div class="heart">
          <button type="submit">
          <i class="fa-solid fa-heart" style="color: #121212;"></i>
          </button>
        </div>

        <!-- Botón de Carrito-->
        <div class="cart">
          <button type="submit">
          <i class="fa-solid fa-cart-shopping" style="color: #121212;"></i>
        </button>
      </div>
    </div>
  </header>


      <!-- Registro -->
      <section class="main-section"></section>
      <div class="sign-up">
        <div class="img-sign-up">
          <img src="../../public/img/foto-login.png" alt="Sign-Up" />
        </div>
        <h2>Iniciar Sesión</h2>
        <h3>Ingresa los detalles abajo</h3>
        <div class="form-sign-up">
          <form id="formLogin" method="post" action="../../controllers/procesoLogin.php">
            <i class="fa-solid fa-envelope" style="color: #0c0c0c;"></i>
            <input type="email" placeholder="Email" id="correo" name="correo" required>
            <br><br>
            <i class="fa-solid fa-lock" style="color: #0c0c0c;"></i>
            <input type="password" placeholder="Contraseña" id="clave" name="clave" required>
            <br><br>
            <div class="buttoncreate">
              <button type="submit">Iniciar Sesión</button>
            </div>
            <div class="a-login"><a href="forgotPassword.html">¿Olvidaste la Contraseña?</a></div>
          </form>
        </div>
      </div>

    <!-- Footer -->
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

  <script src="../../public/js/login.js"></script>
</body>
</html>