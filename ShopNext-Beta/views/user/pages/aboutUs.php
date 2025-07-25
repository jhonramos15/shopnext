<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/aboutUs.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
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
        <a href="index.html">
          <img src="../../../public/img/logo.svg" alt="Logo ShopNext">
        </a>
        <div id="nav">
          <a href="../indexUser.php">Inicio</a>
          <a href="pages/aboutUs.php">Acerca de</a>
          <a href="pages/contact.php">Contacto</a>
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
              <a href="../pages/account.php">Perfil</a>
              <a href="#">Pedidos</a>
              <a href="../../../controllers/logout.php">Cerrar sesión</a>
            </div>
          </div>
    </div>

  </header>

  <section class="main-section">

  <div id="history">
  
    <!-- Contenedor de texto -->
    <div id="text-history">
        <a href="../../public/index.html">Inicio / Acerca de</a>
        <h2>Nuestra Historia</h2>
        <p>
          La creación del sitio web "ShopNext" está orientada a ofrecer beneficios significativos a
          los vendedores locales. Esta plataforma proporcionará a los comerciantes la oportunidad de
          exhibir sus catálogos de productos de manera efectiva, lo que les ayuda a potenciar las
          ventas de sus negocios.
          El sitio cuenta con varios módulos esenciales que facilitan la gestión y la experiencia del
          usuario. El Módulo de Usuarios permite la administración completa de los roles de usuario,
          incluyendo la autenticación y el manejo de diferentes tipos de acceso, como Administrador
          y Cliente.
          Por otro lado, el Módulo de Gestión de Prendas ofrece funcionalidades para agregar, editar
          o eliminar artículos del catálogo de productos. Este módulo es crucial para mantener el
          inventario actualizado y relevante para los compradores.
        </p>
    </div>
  
    <!-- Contenedor de imagen -->
    <div id="img-history">
      <img src="../../../public/img/about-us im.png" alt="About Us">
    </div>
  
  </div>

  <section class="main-section">
  <!-- Estadísticas -->
  <div class="stats-container">
    <div class="stat-box">
      <div class="icon"><i class="fas fa-store"></i></div>
      <h2>10.5k</h2>
      <p>Vendedores activos en el sitio</p>
    </div>
    <div class="stat-box active">
      <div class="icon"><i class="fas fa-dollar-sign"></i></div>
      <h2>33k</h2>
      <p>Ventas mensuales</p>
    </div>
    <div class="stat-box">
      <div class="icon"><i class="fas fa-gift"></i></div>
      <h2>45.5k</h2>
      <p>Clientes activos</p>
    </div>
    <div class="stat-box">
      <div class="icon"><i class="fas fa-sack-dollar"></i></div>
      <h2>25k</h2>
      <p>Venta bruta anual en nuestro sitio</p>
    </div>
  </div>

<section class="team-section">
  <div class="team-member">
    <img src="../../../public/img/Frame 876.png" alt="Perfil Jhon" class="profile-img">
    <h2>Jhon Ramos</h2>
    <h3>Desarrollador Front-End</h3>
    <div class="social-icons">
      <img src="../../../public/img/Icon-Twitter.png" alt="Twitter">
      <img src="../../../public/img/icon-instagram.png" alt="Instagram">
      <img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn">
    </div>
  </div>

  <div class="team-member">
    <img src="../../../public/img/Frame 876.png" alt="Perfil Brayan" class="profile-img">
    <h2>Brayan Ardila</h2>
    <h3>Desarrollador Full-Stack</h3>
    <div class="social-icons">
      <img src="../../../public/img/Icon-Twitter.png" alt="Twitter">
      <img src="../../../public/img/icon-instagram.png" alt="Instagram">
      <img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn">
    </div>
  </div>

    <div class="team-member">
    <img src="../../../public/img/Frame 876.png" alt="Perfil Brayan" class="profile-img">
    <h2>Joseph Vidal</h2>
    <h3>Desarrollador Back-End</h3>
    <div class="social-icons">
      <img src="../../../public/img/Icon-Twitter.png" alt="Twitter">
      <img src="../../../public/img/icon-instagram.png" alt="Instagram">
      <img src="../../../public/img/Icon-Linkedin.png" alt="LinkedIn">
    </div>
  </div>
</section>

  </div>
  <section class="main-section">
  <div class="benefits-container">

    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../../public/img/security.png" alt="Security" />
      </div>
      <h3>ENVÍO RÁPIDO Y GRATUITO</h3>
      <p>Envíos con descuento desde los 140.000 COP</p>
    </div>
  
    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../../public/img/services.png" alt="Customer Support" />
      </div>
      <h3>ATENCIÓN AL CLIENTE 24/7</h3>
      <p>Soporte amigable 24/7</p>
    </div>
  
    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../../public/img/support.png" alt="Guarantee" />
      </div>
      <h3>GARANTÍA DE DEVOLUCIÓN</h3>
      <p>30 días para tu reembolso</p>
    </div>
  
  </div>
  
  <footer>
    <div class="footer-section">
      <img src="../../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
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

    <script src="../../../public/js/account.js"></script>
</head>
</body>