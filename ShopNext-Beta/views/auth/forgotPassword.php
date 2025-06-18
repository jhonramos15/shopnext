<?php echo "<script>console.log('PHP cargado correctamente');</script>"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recupera Cuenta | ShopNext</title>
  <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">
  <link rel="stylesheet" href="../../public/css/forgot-password.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          <img src="../../public/img/logo.svg" alt="Logo ShopNexs">
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
    <div class="forgot">
    <section>

    <h4><a href="login.html"></a>Inicio / Login / Recuperar Cuenta</h4>

<form action="../../core/sendMail.php" method="POST">
    <h2>¡Recupera tu cuenta para acceder a ShopNext!</h2>

    <h3>Correo Electrónico</h3>

    <div class="send-email">
        <input type="email" name="email" placeholder="shopnext@example.com">
        <button type="submit">
            Enviar Correo
            <i class="fa-solid fa-inbox" style="color: #eee;"></i>
        </button>
    </div>
</form>


    </section>
    </div>
    <div class="img-password-forgot"><img src="../../public/img/forgot-pass-img.png" alt="Forgot Pass Img"></div>
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
  <script src="../../public/js/alertas.js"></script>
</body>
</html>