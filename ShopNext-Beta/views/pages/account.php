<?php
// Inicia o reanuda la sesión actual.
session_start();

// 1. INCLUIMOS LOS ARCHIVOS IMPORTANTES
require_once '../../config/conexion.php';
require_once '../../controllers/authGuardCliente.php';

// 2. VERIFICAMOS LA SESIÓN DEL USUARIO
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// 3. OBTENEMOS EL ID Y BUSCAMOS TODOS LOS DATOS DEL USUARIO
$id_usuario = $_SESSION['id_usuario'];

$db = new Conexion();
$conexion = $db->conectar();

// ¡ESTA ES LA CONSULTA CORRECTA! Une las tablas para obtener todos los datos.
$stmt = $conexion->prepare(
    "SELECT 
        u.correo_usuario,
        c.nombre,
        c.telefono,
        c.genero,
        c.fecha_nacimiento,
        c.foto_perfil
    FROM usuario u
    LEFT JOIN cliente c ON u.id_usuario = c.id_usuario
    WHERE u.id_usuario = ?"
);

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// 4. SI EL USUARIO NO EXISTE EN LA BD, LO REDIRIGIMOS
if (!$usuario) {
    header("Location: ../../controllers/logout.php");
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
        <a href="indexUser.php"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="../user/indexUser.php">Inicio</a>
      <a href="../user/pages/contact.php">Contacto</a>
    </nav>

    <!-- Buscador -->
    <div class="header-icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
      <a href="../user/cart/carrito.php"><button class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
      <!-- Ícono de usuario -->
        <div class="user-menu-container">
          <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
          <div class="dropdown-content" id="dropdownMenu">
            <a href="account.php">Perfil</a>
            <a href="../user/pages/pedidos.php">Pedidos</a>
            <a href="../../controllers/logout.php">Cerrar sesión</a>
          </div>
        </div>      
    </div>
  </div>
</header>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="profile-picture-container">
                <img src="/shopnext/ShopNext-Beta/public/uploads/avatars/<?php echo htmlspecialchars($usuario['foto_perfil'] ?? 'default_avatar.png'); ?>" alt="Foto de Perfil" id="profile-pic">
            </div>
            <ul>
                <li class="active"><a href="#">Mi Cuenta</a></li>
                <li><a href="../user/pages/pedidos.php">Mis Pedidos</a></li>
                <li><a href="#">Mis Reseñas</a></li>
                <li><a href="../../controllers/logout.php" style="color: #DB4444;">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <section class="account-content">
            <form id="profile-form" action="/shopnext/ShopNext-Beta/controllers/updatePerfil.php" method="POST" enctype="multipart/form-data">
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

    <footer>
        </footer>

    <script src="../../public/js/alertas.js"></script>
    <script src="../../public/js/account.js"></script>
    <script src="../../public/js/menuHamburguer.js"></script>
    <script src="../../public/js/dropdown.js"></script>

</body>
</html>