<?php
session_start();
// Este guardián asegura que solo los clientes logueados puedan ver esta página.
require_once __DIR__ . '/../../../controllers/authGuardCliente.php';
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

  
<section class="main-section">
    <div class="container-contact">
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div class="info-text">
                <h2>¡Llámanos!</h2>
                <h3>Estamos disponibles las 24 horas, los 7 días de la semana.</h3>
                <h4>Teléfono: +57 213 834 8232</h4>
            </div>
        </div>
        <hr>
        <div class="info-item">
            <div class="info-icon">
                 <i class="fas fa-envelope"></i>
            </div>
            <div class="info-text">
                <h2>¡Escríbenos!</h2>
                <h3>Llena nuestro formulario y te responderemos lo antes posible.</h3>
                <h4>soporteshopnexts@gmail.com</h4>
            </div>
        </div>
    </div>

    <div class="container-form-contact">
        <form id="formContact" action="../../../controllers/user/crearTicket.php" method="POST">
            <div class="form-group">
                <label for="asunto">Asunto *</label>
                <input type="text" id="asunto" name="asunto" required>
            </div>
            <div class="form-group">
                <label for="prioridad">Prioridad *</label>
                <select id="prioridad" name="prioridad" required>
                    <option value="" disabled selected>Selecciona una prioridad...</option>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <div class="form-group">
                 <label for="mensaje">Describe tu problema o inquietud aquí... *</label>
                 <textarea id="mensaje" name="mensaje" required></textarea>
            </div>
            <div class="button-container">
                <button type="submit">Enviar Ticket</button>
            </div>
        </form>
    </div>
</section>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../public/js/contact.js"></script>
    <script src="../../../public/js/alertas.js"></script>
    <script src="../../../public/js/menuHamburguer.js"></script>
</body>
</html>
