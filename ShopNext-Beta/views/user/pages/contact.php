<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/contactUser.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <title>ShopNext | Inicio</title>
</head>
<body>
  
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
        <a href="../indexUser.php">
          <img src="../../../public/img/logo.png" alt="Logo ShopNext">
        </a>
        <div id="nav">
          <a href="../indexUser.php">Inicio</a>
          <a href="#">Productos</a>
          <a href="contact.php">Contacto</a>
        </div>

      <!-- Contenedor de íconos a la derecha -->
      <div class="iconos-derecha">
        <!-- Contenedor de la barra de búsqueda -->
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

        <!-- Ícono de usuario -->
          <div class="user-menu-container">
            <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
            <div class="dropdown-content" id="dropdownMenu">
              <a href="../../pages/account.php">Perfil</a>
              <a href="#">Pedidos 🚧</a>
              <a href="../../../controllers/logout.php">Cerrar sesión</a>
            </div>
          </div>
    </div>

  </header>

  
  <section class="main-section"></section>

    <!-- Contenido de contacto -->
    <div class="container-contact">
        <img src="../../../public/img/icons-phone.png" alt="Teléfono Icon">
        <h2>¡Llámanos!</h2>
        <h3>Estamos disponibles las 24 horas, los 7 días de la semana.</h3>
        <h4>Teléfono: +57 213 834 8232</h4>

        <img src="../../../public/img/icons-mail.png" alt="Mail Icon">
        <h2>¡Escríbenos!</h2>
        <h3>Contacto contigo las 24, los 7 días de la semana.</h3>
        <h4>soporteshopnexts@gmail.com</h4>
    </div>

    <!-- Formulario de contacto -->
    <div class="container-form-contact">
        <form id="formContact">
            <input type="text" placeholder="Tu Nombre *" id="nombre" name="nombre" required style="margin-right: 10px;">
            <input type="email" placeholder="Tu Correo *" id="correo" name="correo" required style="margin-right: 10px;">
            <input type="tel" placeholder="Tu Teléfono" id="telefono" name="telefono" style="margin-right: 10px;">
            <textarea class="container-form-contact-message" placeholder="Tu Mensaje" id="mensaje" name="mensaje" required></textarea>

            <div class="buttoncontact">
                <button type="submit" style="color: white;">Enviar Mensaje</button>
            </div>
        </form>
    </div>

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
    <script src="../../../public/js/contact.js"></script>
</body>
</html>
