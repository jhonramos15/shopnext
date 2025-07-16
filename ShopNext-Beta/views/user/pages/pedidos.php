<?php
session_start();

// Guardián de seguridad para asegurar que el usuario sea un cliente logueado
require_once $_SERVER['DOCUMENT_ROOT'] . '/shopnext/ShopNext-Beta/controllers/authGuardCliente.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtención segura del ID del cliente desde la sesión
$id_usuario_session = $_SESSION['id_usuario'];
$id_cliente = null;

$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario_session);
$stmt_cliente->execute();
$resultado_cliente = $stmt_cliente->get_result();

if ($resultado_cliente->num_rows > 0) {
    $cliente = $resultado_cliente->fetch_assoc();
    $id_cliente = $cliente['id_cliente'];
}
$stmt_cliente->close();

// Obtención de los productos de todos los pedidos del cliente
$items_del_pedido = null;
if ($id_cliente !== null) {
    $sql_items = "SELECT
                      p.nombre_producto,
                      v.nombre AS nombre_vendedor,
                      dp.precio_unitario,
                      dp.cantidad,
                      p.ruta_imagen,
                      pe.id_pedido,
                      pe.fecha,
                      pe.estado
                  FROM pedido pe
                  JOIN detalle_pedido dp ON pe.id_pedido = dp.id_pedido
                  JOIN producto p ON dp.id_producto = p.id_producto
                  JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                  WHERE pe.id_cliente = ?
                  ORDER BY pe.fecha DESC";

    $stmt_items = $conexion->prepare($sql_items);
    $stmt_items->bind_param("i", $id_cliente);
    $stmt_items->execute();
    $items_del_pedido = $stmt_items->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos | ShopNext</title>
    <link rel="stylesheet" href="/shopnext/ShopNext-Beta/public/css/cart/pedido.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
</head>
<body>

<header>
  <div class="header-top">
    <p>Rebajas de Verano: ¡50 % de Descuento!</p>
    <h2>¡Compra Ahora!</h2>
    <select>
      <option value="es">Español</option>
      <option value="en">English</option>
    </select>
  </div>

  <div class="header-main">
    <div class="logo-menu">
      <div class="logo">
        <a href="../../public/index.php"><img src="../../../public/img/logo.svg" alt="ShopNext"></a>
      </div>
      <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
    <nav class="nav-links" id="navMenu">
      <a href="../../public/index.php">Inicio</a>
      <a href="../pages/products/category.php?name=Todos">Productos</a>
      <a href="contact.php">Contacto</a>
    </nav>
    <div class="icons">
      <div class="buscador">
        <input type="text" placeholder="¿Qué estás buscando?">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <button class="icon-btn"><i class="fa-solid fa-heart"></i></button>
        <div class="user-menu-container">
          <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
          <div class="dropdown-content" id="dropdownMenu">
            <a href="account.php">Perfil</a>
            <a href="pedidos.php">Pedidos</a>
            <a href="../../../controllers/logout.php">Cerrar sesión</a>
          </div>
        </div>
    </div>
  </div>
</header>

<main>
    <h1>Mis Pedidos</h1>

    <div class="order-history-container">
        <div class="order-header">
            <div class="header-product">Producto</div>
            <div>Vendedor</div>
            <div>Precio</div>
            <div>Cantidad</div>
            <div>Estado</div>
        </div>

        <?php if ($items_del_pedido && $items_del_pedido->num_rows > 0): ?>
            <?php while ($item = $items_del_pedido->fetch_assoc()): ?>
                <div class="order-item">
                    <div class="product-cell" data-label="Producto">
                        <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($item['ruta_imagen'] ?: 'default.png'); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>" class="product-image">
                        <span><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                    </div>
                    <div data-label="Vendedor"><?php echo htmlspecialchars($item['nombre_vendedor']); ?></div>
                    <div data-label="Precio">$<?php echo number_format($item['precio_unitario']); ?></div>
                    <div data-label="Cantidad"><?php echo htmlspecialchars($item['cantidad']); ?></div>
                    <div data-label="Estado">
                        <span class="status <?php echo strtolower(htmlspecialchars($item['estado'])); ?>">
                            <?php echo ucfirst(htmlspecialchars($item['estado'])); ?>
                        </span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Aún no tienes pedidos. ¡Anímate a comprar!</p>
        <?php endif; ?>
        
        <?php
            if ($items_del_pedido) $stmt_items->close();
            $conexion->close();
        ?>
    </div>
</main>

<footer class="footer-contact">
    </footer>
<script src="../../../public/js/dropdown.js"></script>
<script src="../../../public/js/menuHamburguer.js"></script>
</body>
</html>