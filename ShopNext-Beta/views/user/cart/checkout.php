<?php
session_start();

// ===== AÑADE ESTE BLOQUE AL INICIO DE TODO =====
session_start();

// Si no existe la sesión del usuario, redirigirlo al login
if (!isset($_SESSION['user_id'])) {
    // Puedes añadir un mensaje para que el usuario sepa por qué fue redirigido
    header("Location: ../../auth/login.php?redirect=checkout");
    exit();
}
// ===============================================

// El resto de tu código HTML y PHP de la página de checkout continúa aquí abajo
require_once('../../../config/conexion.php');
// ...etc.

// Esto elimina CUALQUIER error de 'require_once' o de archivos corruptos.
class ConexionCheckout {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $db = "shopnexs";

    public function conectar() {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conexion = new mysqli($this->host, $this->usuario, $this->password, $this->db);

        if ($conexion->connect_error) {
            die("Error REAL de la Base de Datos: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
        }
        
        $conexion->set_charset("utf8");
        return $conexion;
    }
}
// --- FIN DE LA CLASE DE CONEXIÓN ---

// PASO 2: Usamos la clase que acabamos de definir.
$db = new ConexionCheckout();
$conexion = $db->conectar();

if (!$conexion) {
    // Si este mensaje aparece ahora, el problema es 100% del servidor MySQL.
    die("Error Inesperado: La conexión falló incluso estando en el mismo archivo.");
}

// PASO 3: El resto de tu código para el carrito
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$cliente_res = $stmt_cliente->get_result();
$id_cliente = ($cliente_res->num_rows > 0) ? $cliente_res->fetch_assoc()['id_cliente'] : 0;

$id_carrito = 0;
if ($id_cliente > 0) {
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $carrito_res = $stmt_carrito->get_result();
    $id_carrito = ($carrito_res->num_rows > 0) ? $carrito_res->fetch_assoc()['id_carrito'] : 0;
}

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
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../../../public/css/checkout.css">
</head>
<body>

    <div class="container">
        <div class="billing-details">
            <h1>Detalles de Facturación</h1>
            <form id="checkout-form" action="/shopnext/ShopNext-Beta/controllers/cart/checkoutController.php" method="POST">
                <div class="form-group">
                    <label for="first-name">Nombre*</label>
                    <input type="text" id="first-name" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="company-name">Nombre de la Empresa</label>
                    <input type="text" id="company-name" name="companyname">
                </div>
                <div class="form-group">
                    <label for="street-address">Dirección*</label>
                    <input type="text" id="street-address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="apartment">Apartamento, piso, etc. (opcional)</label>
                    <input type="text" id="apartment" name="apartment">
                </div>
                <div class="form-group">
                    <label for="town-city">Localidad/Ciudad*</label>
                    <input type="text" id="town-city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="phone-number">Número de Teléfono*</label>
                    <input type="tel" id="phone-number" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email-address">Correo Electrónico*</label>
                    <input type="email" id="email-address" name="email" required>
                </div>
                <div class="save-info">
                    <input type="checkbox" id="save-info" name="saveinfo">
                    <label for="save-info">Guardar esta información para la próxima vez</label>
                </div>
            </form>
        </div>

        <div class="order-summary">
            <?php if (count($items_del_carrito) > 0): ?>
                <?php foreach ($items_del_carrito as $item): ?>
                <div class="order-item">
                    <div class="item-info">
                        <img src="/shopnext/ShopNext-Beta/public/uploads/products/<?php echo htmlspecialchars($item['ruta_imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                        <span><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                    </div>
                    <span>$<?php echo number_format($item['precio'] * $item['cantidad']); ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos en tu carrito.</p>
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
                    <span class="bank-icons">
                        <img src="../../../public/img/checkout/descarga (4).png" alt="Bkash">
                        <img src="../../../public/img/checkout/Visa Logo PNG.png" alt="Visa">
                        <img src="../../../public/img/checkout/Mastercard.png" alt="Mastercard">
                        <img src="../../../public/img/checkout/New logo PayPal  PayPal + FuseProject (LA).png" alt="PayPal">
                    </span>
                </div>
                <div>
                    <input type="radio" id="cash" name="payment" value="cash" form="checkout-form">
                    <label for="cash">Pago contra entrega</label>
                </div>
            </div>

            <div class="coupon-section">
                <input type="text" id="coupon-code" name="coupon_code" placeholder="Código de Cupón" form="checkout-form">
                <button type="button" class="apply-coupon-btn">Aplicar Cupón</button>
            </div>

            <button type="submit" form="checkout-form" class="place-order-btn">Realizar Pedido</button>
        </div>
    </div>

    <script src="../../../public/js/cart/checkout.js"></script>
    <script src="../../../public/js/alertas.js"></script>
</body>
</html>