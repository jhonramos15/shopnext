<?php
session_start();
require_once __DIR__ . '/../../../controllers/authGuardCliente.php';

$conexion = new mysqli("localhost", "root", "", "shopnexs");
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$id_cliente = $stmt_cliente->get_result()->fetch_assoc()['id_cliente'];

// Consulta para obtener todos los pedidos del cliente
$sql_pedidos = "SELECT 
                    p.id_pedido, 
                    p.fecha, 
                    p.estado, 
                    v.nombre as nombre_vendedor, 
                    SUM(dp.cantidad * dp.precio_unitario) as total
                FROM pedido p
                JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                WHERE p.id_cliente = ?
                GROUP BY p.id_pedido, v.nombre
                ORDER BY p.fecha DESC";

$stmt = $conexion->prepare($sql_pedidos);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
        <a href="../../public/index.php"><img src="../../public/img/logo.svg" alt="ShopNext"></a>
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
      <!-- Iniciar Sesión -->
      <a href="../auth/login.php" class="login-btn">Iniciar Sesión</a>
    </div>
  </div>
</header>
    <div class="container">
        <h1>Mis Pedidos</h1>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'compra_exitosa'): ?>
            <div class="alerta-exito">¡Tu compra se ha realizado con éxito!</div>
        <?php endif; ?>

        <?php if (count($pedidos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Vendido por</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_vendedor']); ?></td>
                            <td>$<?php echo number_format($pedido['total']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aún no has realizado ningún pedido.</p>
        <?php endif; ?>
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
</body>
</html>