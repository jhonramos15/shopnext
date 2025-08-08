<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/user/account.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="icon" href="../../public/img/icon_principal.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>ShopNext | Mi Cuenta</title>
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
        <a href="indexUser.php"><img src="img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
        <a href="/shopnext/ShopNext-Beta/public/index.php">Inicio</a>
        <a href="?action=products">Productos</a>
        <a href="?action=contact">Contacto</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <a href="../user/cart/carrito.php"><button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
        <!-- Ícono de usuario, solo si está logueado -->
        <div class="user-menu-container">
            <i class="fas fa-user user-icon"></i>
            
            <div class="dropdown-content" id="dropdownMenu">
              <a href="?action=account">
                <i class="fas fa-user-circle"></i> <span>Mi Perfil</span>
              </a>
              <a href="/shopnext/ShopNext-Beta/views/user/pages/pedidos.php">
                <i class="fas fa-box"></i> <span>Mis Pedidos</span>
              </a>
              <hr>
              <a href="index.php?action=logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
              </a>
            </div>
        </div>      
    </div>
  </div>
</header>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="profile-picture-container">
                <img src="<?php echo BASE_URL; ?>uploads/avatars/<?php echo htmlspecialchars($usuario['foto_perfil'] ?? 'default_avatar.png'); ?>" alt="Foto de Perfil" id="profile-pic">
            </div>
            <ul>
                <li class="active"><a href="#">Mi Cuenta</a></li>
                <li><a href="../user/pages/pedidos.php">Mis Pedidos</a></li>
                <li><a href="#">Mis Reseñas</a></li>
                <li><a href="../../controllers/logout.php" style="color: #DB4444;">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <section class="account-content">
            <form id="profile-form" action="index.php?action=update-profile" method="POST" enctype="multipart/form-data">
                <h2>Editar Perfil</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="correo" value="<?php echo htmlspecialchars($usuario['correo_usuario'] ?? ''); ?>" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento'] ?? ''); ?>" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="genero">Género</label>
                        <select id="genero" name="genero" disabled>
                            <option value="">Seleccionar...</option>
                            <option value="Masculino" <?php echo (($usuario['genero'] ?? '') == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo (($usuario['genero'] ?? '') == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                            <option value="Otro" <?php echo (($usuario['genero'] ?? '') == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="profile-pic-upload">Cambiar Foto</label>
                        <input type="file" id="profile-pic-upload" name="foto_perfil" accept="image/*" disabled>
                    </div>
                </div>
                
                <div class="password-section">
                    <h3>Cambiar Contraseña</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="password" id="current_password" name="current_password" placeholder="Contraseña Actual" disabled>
                        </div>
                        <div class="form-group">
                            <input type="password" id="new_password" name="new_password" placeholder="Nueva Contraseña" disabled>
                        </div>
                        <div class="form-group">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña" disabled>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="edit-profile-btn" class="btn-edit">Editar Perfil</button>
                    <button type="button" id="cancel-edit-btn" class="btn-cancel" style="display: none;">Cancelar</button>
                    <button type="submit" class="btn-save" style="display: none;">Guardar Cambios</button>
                </div>
            </form>
        </section>
    </main>
<footer class="footer-contact">
  <div class="footer-section">
    <img src="img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo">
  </div>
  <div class="footer-section">
    <h3>Información</h3>
    <ul>
      <li><a href="aboutUs.html">Acerca de</a></li>
      <li><a href="contact.html">Contacto</a></li>
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
    <ul class="social-icons">
      <li><a href="#"><img src="img/Icon-Twitter.png" alt="Twitter"></a></li>
      <li><a href="#"><img src="img/icon-instagram.png" alt="Instagram"></a></li>
      <li><a href="#"><img src="img/Icon-Linkedin.png" alt="LinkedIn"></a></li>
    </ul>
  </div>
</footer>

    <script src="js/common/alertas.js"></script>
    <script src="js/user/account.js"></script>
    <script src="js/common/menu-hamburguer.js"></script>
    <script src="js/user/dropdown.js"></script>

</body>
</html>