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
    <link rel="stylesheet" href="../../public/css/account.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <title>ShopNext | Mi Cuenta</title>
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
          <img src="../../public/img/logo.svg" alt="Logo ShopNext">
        </a>
        <div id="nav">
          <a href="index.html">Inicio</a>
          <a href="../user/pages/aboutUs.php">Acerca de</a>
          <a href="../user/pages/contact.php">Contacto</a>
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
              <a href="account.html">Perfil</a>
              <a href="#">Pedidos</a>
              <a href="#">Cerrar sesión</a>
            </div>
          </div>
    </div>
  </header>
  <section></section>
        <a href="../../public/index.html">Inicio / Mi Cuenta</a>

        <h2>Administrar Mi Cuenta</h2>

        <div class="my-profile">

            <h2>Nombre</h2>
            <input type="text" placeholder="Ej: Daniel" id="nombre" name="nombre">

            <h2>Correo</h2>
            <input type="email" placeholder="Ej: daniel@gmail.com" id="email" name="email">

            <h2>Dirección</h2>
            <input type="address" placeholder="Ej: Calle 323 # 39 - 9" id="address" name="address">

            <h2>Cambiar Contraseña</h2>
            <input type="email" placeholder="Contraseña Actual" id="password" name="password">
            <input type="password" placeholder="Nueva Contraseña" id="password" name="password">
            <input type="password" placeholder="Confirmar Nueva Contraseña" id="password" name="password">

            <div class="cancelbutton">
              <a href="../../public/index-user.php"><button>Cancelar</button></a>
            </div>

            <div class="buttoncreate">
              <button type="submit">Guardar Cambios</button>
            </div>
          
            <div class="a-login"><a href="../auth/forgotPassword.html">¿Olvidaste la Contraseña?</a></div>
        </div>
        </section>

        <footer>
      <div class="footer-section">
        <img src="img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo"> <!-- Agregar el logo correspondiente-->
      </div>
  
      <div class="send-message">
        <input type="text" placeholder="Envía un correo">
        <button type="submit">
          <i class="fa-solid fa-paper-plane" style="color: #0c0c0c;"></i>
        </button>
      </div>
    
      <div class="footer-section">
        <h3>Información</h3>
        <ul>
          <li><a href="aboutUs.html">Acerca de</a></li>
          <li><a href="contact.php">Contacto</a></li>
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
        <ul>
          <li><a>Redes Sociales</a></li>
  
          <img src="../img/Icon-Twitter.png" alt="Icon Twitter">
          <img src="../img/icon-instagram.png" alt="Icon Instagram">
          <img src="../img/Icon-Linkedin.png" alt="Icon LinkedIn">
        </ul>
      </div>
    </footer>
    <script src="../../public/js/account.js"></script>
</body>
</html>