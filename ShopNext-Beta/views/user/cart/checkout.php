<?php
session_start();
// Guardián para proteger la página
require_once __DIR__ . '/../../../controllers/authGuardCliente.php';

// Conexión a la BD
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtenemos el id_cliente para buscar su carrito
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$cliente_res = $stmt_cliente->get_result();
$id_cliente = ($cliente_res->num_rows > 0) ? $cliente_res->fetch_assoc()['id_cliente'] : 0;

// Obtenemos el id_carrito
$id_carrito = 0;
if ($id_cliente > 0) {
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $carrito_res = $stmt_carrito->get_result();
    $id_carrito = ($carrito_res->num_rows > 0) ? $carrito_res->fetch_assoc()['id_carrito'] : 0;
}

// Obtenemos los productos del carrito
$items_del_carrito = [];
if ($id_carrito > 0) {
    $sql_items = "SELECT p.nombre_producto, p.precio, p.ruta_imagen, pc.cantidad
                  FROM producto_carrito pc
                  JOIN producto p ON pc.id_producto = p.id_producto
                  WHERE pc.id_carrito = ?";
    $stmt_items = $conexion->prepare($sql_items);
    $stmt_items->bind_param("i", $id_carrito);
    $stmt_items->execute();
    $resultado_items = $stmt_items->get_result();
    $items_del_carrito = $resultado_items->fetch_all(MYSQLI_ASSOC);
}

// Calcular el total
$total_pedido = 0;
foreach ($items_del_carrito as $item) {
    $total_pedido += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra | ShopNext</title>
    <link rel="stylesheet" href="../../../public/css/checkout.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
</head>
<body>
        <div class="container">
        <div class="checkout-layout">
            
            <div class="checkout-form">
                <form action="/shopnext/ShopNext-Beta/controllers/cart/checkoutController.php" method="POST" id="checkout-form">
                    <div class="billing-details">
                        <h3>Detalles de Facturación</h3>
                        <div class="form-group">
                            <label for="fname">Nombre *</label>
                            <input type="text" id="fname" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="lname">Apellido *</label>
                            <input type="text" id="lname" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="adr">Dirección *</label>
                            <input type="text" id="adr" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="city">Ciudad *</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notas del pedido (opcional)</label>
                            <textarea id="notes" name="notes" placeholder="Notas sobre tu pedido, ej. notas especiales para la entrega."></textarea>
                        </div>
                    </div>
                </form> 
            </div>

            <div class="order-summary">
                <h3>Tu Pedido</h3>
                <?php if (count($items_del_carrito) > 0): ?>
                    <?php foreach ($items_del_carrito as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($item['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                            <span><?php echo htmlspecialchars($item['nombre_producto']); ?> (x<?php echo $item['cantidad']; ?>)</span>
                        </div>
                        <span>$<?php echo number_format($item['precio'] * $item['cantidad']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="order-total">
                    <div>
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($total_pedido); ?></span>
                    </div>
                    <div>
                        <span>Envío:</span>
                        <span>Gratis</span>
                    </div>
                    <div class="total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total_pedido); ?></span>
                    </div>
                </div>

                <div class="payment-method">
                    <div>
                        <input type="radio" id="bank" name="payment" value="bank" checked form="checkout-form">
                        <label for="bank">Transferencia Bancaria</label>
                    </div>
                    <div>
                        <input type="radio" id="cash" name="payment" value="cash" form="checkout-form">
                        <label for="cash">Pago contra entrega</label>
                    </div>
                </div>

                <div class="coupon-section">
                    <input type="text" id="coupon-code" placeholder="Código de Cupón" form="checkout-form">
                    <button type="button" class="apply-coupon-btn">Aplicar</button>
                </div>
                
                <button type="submit" form="checkout-form" class="place-order-btn">Realizar Pedido</button>
            </div>
        </div>
    </div>
    <script src="../../../public/js/cart/checkout.js"></script>
</body>
</html>