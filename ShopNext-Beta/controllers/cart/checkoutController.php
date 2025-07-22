<?php
session_start();

// --- 1. INCLUIR LAS CLASES DE PHPMailer ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- 2. REQUERIR LOS ARCHIVOS DE PHPMailer ---
require __DIR__ . '/../../core/PHPMailer/src/Exception.php';
require __DIR__ . '/../../core/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../../core/PHPMailer/src/SMTP.php';

// --- 3. REQUERIR ARCHIVOS DEL PROYECTO ---
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../controllers/authGuardCliente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($conexion) || !$conexion) {
        die("Error fatal: La conexión a la base de datos no está disponible.");
    }

    // --- 4. OBTENER DATOS COMPLETOS DEL USUARIO ---
    $id_usuario = $_SESSION['id_usuario'];
    $stmt_user_data = $conexion->prepare(
        "SELECT c.id_cliente, c.nombre, u.correo_usuario 
         FROM cliente c 
         JOIN usuario u ON c.id_usuario = u.id_usuario 
         WHERE c.id_usuario = ?"
    );
    $stmt_user_data->bind_param("i", $id_usuario);
    $stmt_user_data->execute();
    $user_data_result = $stmt_user_data->get_result();

    if ($user_data_result->num_rows === 0) {
        die("Error: No se encontraron datos completos del cliente.");
    }
    $user_data = $user_data_result->fetch_assoc();
    $id_cliente = $user_data['id_cliente'];
    $cliente_nombre = $user_data['nombre'];
    $cliente_email = $user_data['correo_usuario'];

    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $id_carrito = $stmt_carrito->get_result()->fetch_assoc()['id_carrito'];

    $conexion->begin_transaction();
    try {
        // --- Lógica de Pedidos (sin cambios) ---
        $sql_items = "SELECT pc.id_producto, p.nombre_producto, p.id_vendedor, pc.cantidad, p.precio FROM producto_carrito pc JOIN producto p ON pc.id_producto = p.id_producto WHERE pc.id_carrito = ?";
        $stmt_items = $conexion->prepare($sql_items);
        $stmt_items->bind_param("i", $id_carrito);
        $stmt_items->execute();
        $items_del_carrito = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);

        if (empty($items_del_carrito)) { throw new Exception("El carrito está vacío."); }

        $pedidos_por_vendedor = [];
        foreach ($items_del_carrito as $item) {
            $id_vendedor = $item['id_vendedor'];
            if (!isset($pedidos_por_vendedor[$id_vendedor])) {
                $pedidos_por_vendedor[$id_vendedor] = ['items' => [], 'total' => 0];
            }
            $pedidos_por_vendedor[$id_vendedor]['items'][] = $item;
            $pedidos_por_vendedor[$id_vendedor]['total'] += $item['precio'] * $item['cantidad'];
        }

        foreach ($pedidos_por_vendedor as $id_vendedor => $data_pedido) {
            $stmt_pedido = $conexion->prepare("INSERT INTO pedido (id_cliente, id_vendedor, fecha, estado) VALUES (?, ?, NOW(), 'pendiente')");
            $stmt_pedido->bind_param("ii", $id_cliente, $id_vendedor);
            $stmt_pedido->execute();
            $id_pedido_nuevo = $conexion->insert_id;
            foreach ($data_pedido['items'] as $producto) {
                $stmt_detalle = $conexion->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
                $stmt_detalle->bind_param("iiid", $id_pedido_nuevo, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
                $stmt_detalle->execute();
                $stmt_stock = $conexion->prepare("UPDATE producto SET stock = stock - ? WHERE id_producto = ?");
                $stmt_stock->bind_param("ii", $producto['cantidad'], $producto['id_producto']);
                $stmt_stock->execute();
            }
        }
        
        // --- 5. AHORA SÍ: LÓGICA DEL CORREO ORDENADA ---

        // PASO A: PRIMERO se prepara el contenido del correo.
        $productos_html = '';
        $total_general = 0;
        foreach ($items_del_carrito as $producto) {
            $subtotal_producto = $producto['precio'] * $producto['cantidad'];
            $total_general += $subtotal_producto;
            $productos_html .= '<tr><td style="padding: 12px 0; border-bottom: 1px solid #eeeeee;"><p style="margin: 0;">' . htmlspecialchars($producto['nombre_producto']) . ' (x' . $producto['cantidad'] . ')</p></td><td style="text-align: right;">$' . number_format($subtotal_producto, 0, ',', '.') . '</td></tr>';
        }
 // 2. Construimos la plantilla HTML completa
$cuerpo_correo = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Poppins, sans-serif; margin: 0; padding: 0; background-color: #f6f9fc; }
    .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .header { background-color: #8E06C2; padding: 40px; text-align: center; }
    .header img { max-width: 150px; }
    .content { padding: 40px 30px; }
    .footer { background-color: #121212; padding: 30px; text-align: center; color: #aaaaaa; font-size: 12px; }
    .button { display: inline-block; padding: 12px 25px; background-color: #8E06C2; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 500; }
</style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <img src="https://imgur.com/a/TGn6Pon" alt="ShopNext Logo">
            </div>
            <div class="content">
                <h1 style="color: #121212; font-size: 24px; margin-bottom: 15px;">¡Gracias por tu compra, ' . htmlspecialchars($cliente_nombre) . '!</h1>
                <p style="color: #555555; line-height: 1.6; margin-bottom: 25px;">Tu pedido ha sido recibido y está siendo procesado. Aquí tienes un resumen de tu compra:</p>
                
                <h3 style="color: #121212; font-size: 18px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px;">Resumen de tu compra</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    ' . $productos_html . '
                </table>
                
                <table style="width: 100%; margin-top: 30px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 15px 0; font-size: 18px; font-weight: 600; color: #333333; border-top: 2px solid #eeeeee;">Total Pagado</td>
                        <td style="padding: 15px 0; font-size: 18px; font-weight: 600; color: #8E06C2; text-align: right; border-top: 2px solid #eeeeee;">$' . number_format($total_general, 0, ',', '.') . '</td>
                    </tr>
                </table>

                <p style="text-align: center; margin-top: 40px;">
                    <a href="http://localhost/shopnext/ShopNext-Beta/views/user/pages/pedidos.php" class="button">Ver mis compras</a>
                </p>
            </div>
            <div class="footer">
                <p>ShopNext &copy; ' . date("Y") . ' - Todos los derechos reservados.</p>
                <p>Si tienes alguna pregunta, contáctanos a soporteshopnexts@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>';
        
        // PASO B: LUEGO se intenta enviar.
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shopnextnoreply@gmail.com';
            $mail->Password   = 'uhym jhjw dzym pyyf';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom('shopnextnoreply@gmail.com', 'ShopNext');
            $mail->addAddress($cliente_email, $cliente_nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de tu compra en ShopNext';
            $mail->Body    = $cuerpo_correo;
            $mail->send();
        } catch (Exception $e) {
            // Si el correo falla, no detenemos la compra. Opcionalmente se puede registrar el error.
            // error_log("No se pudo enviar el correo de confirmación. Mailer Error: {$mail->ErrorInfo}");
        }

        // --- 6. Finalizar la transacción ---
        $stmt_vaciar = $conexion->prepare("DELETE FROM producto_carrito WHERE id_carrito = ?");
        $stmt_vaciar->bind_param("i", $id_carrito);
        $stmt_vaciar->execute();
        
        $conexion->commit();
        header("Location: /shopnext/ShopNext-Beta/views/user/pages/pedidos.php?status=compra_exitosa");
        exit;

    } catch (Exception $e) {
        $conexion->rollback();
        header("Location: /shopnext/ShopNext-Beta/views/user/cart/checkout.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: /shopnext/ShopNext-Beta/views/user/cart/carrito.php");
    exit;
}
?>