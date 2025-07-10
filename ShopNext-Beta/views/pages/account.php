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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>ShopNext | Mi Cuenta</title>
</head>
<body>

  <!-- Alerta Descuento -->
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
        <a href="indexUser.php"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="indexUser.php">Inicio</a>
      <a href="../../views/auth/signUp.html">Regístrate</a>
      <a href="../../views/pages/contact.html">Contacto</a>
      <a href="../../views/pages/aboutUs.html">Acerca de</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button>
      <!-- Ícono de usuario -->
        <div class="user-menu-container">
          <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
          <div class="dropdown-content" id="dropdownMenu">
            <a href="../pages/account.php">Perfil</a>
            <a href="#">Pedidos 🚧</a>
            <a href="../../controllers/logout.php">Cerrar sesión</a>
          </div>
        </div>      
    </div>
  </div>
</header>
</section>

<section class="profile-container">
  <div id="aAccount"><a href="../user/indexUser.php">Inicio / Mi Cuenta</a></div>
  <form id="formEditarPerfil" class="profile-form" method="POST" action="../../controllers/actualizarPerfilCliente.php">
    <h2>Editar Perfil</h2>

    <div class="form-group">
      <input type="text" placeholder="Tu Nombre" id="nombre" name="nombre">
    </div>

    <div class="form-group">
      <input type="email" placeholder="Correo Electrónico" id="email" name="email">
    </div>

    <div class="form-group">
      <input type="text" placeholder="Dirección" id="address" name="address">
    </div>

    <h3>Cambiar Contraseña</h3>

    <div class="form-group">
      <input type="password" placeholder="Contraseña Actual" name="old_password">
    </div>
    <div class="form-group">
      <input type="password" placeholder="Nueva Contraseña" name="new_password">
    </div>
    <div class="form-group">
      <input type="password" placeholder="Confirmar Nueva Contraseña" name="confirm_password">
    </div>

    <div class="button-group">
      <a href="../user/indexUser.php" class="cancel-btn">Cancelar</a>
      <button type="submit" class="save-btn">Guardar Cambios</button>
    </div>

    <div class="forgot-password">
      <a href="../auth/forgotPassword.html">¿Olvidaste la Contraseña?</a>
    </div>
  </form>
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
    <script src="../../public/js/alertas.js"></script>
    <script src="../../public/js/account.js"></script>
    <script src="../../public/js/menuHamburguer.js"></script>
</body>
</html>