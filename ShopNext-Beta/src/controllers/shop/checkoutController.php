<?php
session_start(); // <-- Punto y coma añadido

// ===== ¡IMPORTANTE! AÑADE ESTAS DOS LÍNEAS AL INICIO =====
// Forzarán a PHP a mostrarte cualquier error que esté oculto.
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==========================================================

// Asegúrate de que las rutas a tus archivos sean correctas
require_once(__DIR__ . '/../../config/conexion.php'); 
require_once(__DIR__ . '/../../models/usuario.php');

class CheckoutController {
    private $conexion;
    private $modeloUsuario;

    public function __construct() {
        // 1. Creamos un objeto de la clase Conexion
        $conexionObj = new Conexion(); 
        // 2. Usamos su método para obtener la conexión real (el objeto mysqli)
        $this->conexion = $conexionObj->getConexion(); 
        // 3. Pasamos la conexión al modelo de usuario
        $this->modeloUsuario = new Usuario($this->conexion);
    }

    public function procesarPedido() {
        // La sesión ya se inició al principio del archivo
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_SESSION['user_id'])) {
                die("Error crítico: No se puede procesar el pedido porque el usuario no ha iniciado sesión.");
            }

            // Validar que el carrito no esté vacío
            if (empty($_SESSION['carrito'])) {
                die("Error: El carrito está vacío, no se puede procesar el pedido.");
            }

            // Recoger datos del formulario
            $userId = $_SESSION['user_id'];
            $nombreCompleto = $_POST['nombreCompleto'];
            $direccion = $_POST['direccion'];
            $ciudad = $_POST['ciudad'];
            $estado = $_POST['estado'];
            $codigoPostal = $_POST['codigo_postal'];
            $pais = $_POST['pais'];
            $total = $_POST['total'];

            $this->conexion->begin_transaction();

            try {
                // Insertar en la tabla 'pedidos'
                $sqlPedido = "INSERT INTO pedidos (user_id, nombre_completo, direccion, ciudad, estado, codigo_postal, pais, total, estado_pedido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Procesando')";
                $stmt = $this->conexion->prepare($sqlPedido);

                if ($stmt === false) {
                    throw new Exception("Error al preparar la consulta de pedido: " . $this->conexion->error);
                }

                $stmt->bind_param("issssssd", $userId, $nombreCompleto, $direccion, $ciudad, $estado, $codigoPostal, $pais, $total);
                $stmt->execute();
                $pedidoId = $stmt->insert_id;
                $stmt->close();

                // Insertar cada producto del carrito en 'detalles_pedidos'
                $sqlDetalle = "INSERT INTO detalles_pedidos (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmtDetalle = $this->conexion->prepare($sqlDetalle);

                 if ($stmtDetalle === false) {
                    throw new Exception("Error al preparar la consulta de detalles del pedido: " . $this->conexion->error);
                }

                foreach ($_SESSION['carrito'] as $productoId => $item) {
                    $stmtDetalle->bind_param("iiid", $pedidoId, $productoId, $item['cantidad'], $item['precio']);
                    $stmtDetalle->execute();
                }
                $stmtDetalle->close();

                $this->conexion->commit();
                
                unset($_SESSION['carrito']);

                header("Location: ../../views/user/pages/pedidos.php?status=success&pedido_id=" . $pedidoId);
                exit();

            } catch (Exception $e) {
                $this->conexion->rollback();
                header("Location: ../../views/user/cart/checkout.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
        } else {
             header("Location: ../../views/user/cart/checkout.php");
             exit();
        }
    }
}

// Crea una instancia del controlador y ejecuta el método.
$checkoutController = new CheckoutController();
$checkoutController->procesarPedido();
?>