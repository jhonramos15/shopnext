<?php
session_start();

// Usamos la ruta absoluta que ya confirmamos que funciona.
require_once $_SERVER['DOCUMENT_ROOT'] . '/shopnext/ShopNext-Beta/controllers/authGuardCliente.php';

// Conexión a la BD
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

// --- OBTENCIÓN SEGURA DEL ID DEL CLIENTE ---
$id_usuario_session = $_SESSION['id_usuario'];
$id_cliente = null; // Inicializamos como null por seguridad

$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario_session);
$stmt_cliente->execute();
$resultado_cliente = $stmt_cliente->get_result();

// ¡AQUÍ ESTÁ LA CORRECCIÓN CLAVE!
// Solo continuamos si encontramos un perfil de cliente.
if ($resultado_cliente->num_rows > 0) {
    $cliente = $resultado_cliente->fetch_assoc();
    $id_cliente = $cliente['id_cliente'];
}
$stmt_cliente->close();

// --- OBTENCIÓN DE PEDIDOS (SOLO SI TENEMOS UN CLIENTE) ---
$resultado_pedidos = null; // Inicializamos la variable de pedidos

if ($id_cliente !== null) {
    $sql_pedidos = "SELECT 
                        pe.id_pedido, 
                        pe.fecha, 
                        pe.total, 
                        pe.estado_pago
                    FROM pedidos pe
                    WHERE pe.id_cliente = ?
                    ORDER BY pe.fecha DESC";

    $stmt_pedidos = $conexion->prepare($sql_pedidos);
    $stmt_pedidos->bind_param("i", $id_cliente);
    $stmt_pedidos->execute();
    $resultado_pedidos = $stmt_pedidos->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="/shopnext/ShopNext-Beta/public/css/cart/pedido.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
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
        <a href="../../public/index.php"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <!-- Menú Hamburguesa -->
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Nav Menú -->
    <nav class="nav-links" id="navMenu">
      <a href="../../public/index.php">Inicio</a>
      <a href="../auth/signUp.html">Regístrate</a>
      <a href="contact.html">Contacto</a>
      <a href="aboutUs.html">Acerca de</a>
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
    <div class="dashboard">
        <aside class="sidebar">
            </aside>

        <main class="main">
            <header class="header">
                <h1>Gestión de Pedidos</h1>
                </header>

            <section class="cards">


            <section class="table-section">
                <div class="table-header">
                    <h2>Historial de Pedidos</h2>
                    </div>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado_pedidos && $resultado_pedidos->num_rows > 0) {
                            while ($fila = $resultado_pedidos->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td class="product-cell">
                                        <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($fila['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($fila['nombre_producto']); ?>" class="product-image">
                                        <span><?php echo htmlspecialchars($fila['nombre_producto']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($fila['nombre_cliente']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($fila['fecha'])); ?></td>
                                    <td>$<?php echo number_format($fila['total'], 0); ?></td>
                                    <td>
                                        <span class="status paid"><?php echo htmlspecialchars($fila['estado_pago']); ?></span>
                                    </td>
                                    <td class="table-actions">
                                        <div class="action-icons">
                                            <a href="#" class="action-icon" title="Ver Detalles"><i data-lucide="eye"></i></a>
                                            <a href="#" class="action-icon" title="Imprimir Factura"><i data-lucide="printer"></i></a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            } // Fin del while
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Aún no tienes pedidos para tus productos.</td></tr>";
                        }
                        $stmt_pedidos->close();
                        $conexion->close();
                        ?>
                    </tbody>
                </table>
                </section>
        </main>
    </div>
    <footer class="footer-contact">
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
              <img src="../../../public/img/Icon-Twitter.png" alt="Icon Twitter">
              <img src="../../../public/img/icon-instagram.png" alt="Icon Instagram">
              <img src="../../../public/img/Icon-Linkedin.png" alt="Icon LinkedIn">
            </ul>
          </div>
    </footer>
<script src="../../../public/js/dropdown.js"></script>
</body>
</html>