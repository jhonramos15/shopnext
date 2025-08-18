<?php
namespace App\Controllers\Shop;

use App\Models\CartModel;
use App\Models\ClienteModel;
use Config\Database;
use App\Core\SessionManager; // Es buena práctica iniciar la sesión aquí.

class CartController
{
    private $cartModel;
    private $clienteModel;

    public function __construct()
    {
        $database = new Database();
        $db_connection = $database->getConnection();
        $this->cartModel = new CartModel();
        $this->clienteModel = new ClienteModel(); 
    }

    /**
     * Muestra la página del carrito con todos sus productos.
     */
    public function show()
    {
        SessionManager::start(); // Inicia la sesión de forma segura.
        if (!SessionManager::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit;
        }

        $id_usuario = SessionManager::get('id_usuario');
        $cliente = $this->clienteModel->findByUsuarioId($id_usuario);
        
        if (!$cliente) {
            die("Error: No se encontró el cliente asociado al usuario.");
        }

        $id_carrito = $this->cartModel->getOrCreateCart($cliente['id_cliente']);
        $items_del_carrito = $this->cartModel->getCartItems($id_carrito);
        
        // La vista solo necesita los items, no tiene que saber nada más.
        require_once __DIR__ . '/../../../views/user/cart/carrito.php';
    }

    /**
     * Maneja las acciones de la API (añadir, eliminar, vaciar).
     */
    /**
     * Maneja las acciones de la API (añadir, eliminar, etc.).
     */
    public function handleApiAction()
    {
        SessionManager::start();
        header('Content-Type: application/json');

        if (!SessionManager::isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para añadir productos al carrito.']);
            exit;
        }

        $id_usuario = SessionManager::get('id_usuario');
        $cliente = $this->clienteModel->findByUsuarioId($id_usuario);
        if (!$cliente) {
            echo json_encode(['success' => false, 'message' => 'Perfil de cliente no válido.']);
            exit;
        }
        
        $id_carrito = $this->cartModel->getOrCreateCart($cliente['id_cliente']);
        $action = $_POST['action'] ?? '';
        $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        $success = false;
        $message = 'Acción no reconocida.';

        switch ($action) {
            case 'add':
                if ($id_producto > 0) {
                    $success = $this->cartModel->addItem($id_carrito, $id_producto, $cantidad);
                    $message = $success ? 'Producto añadido al carrito.' : 'No se pudo añadir el producto.';
                } else {
                    $message = 'ID de producto no válido.';
                }
                break;
            // ... (tus otros casos: 'remove', 'clear', etc.)
        }
        
        echo json_encode(['success' => $success, 'message' => $message]);
        $success = false;
        switch ($action) {
            case 'add':
                if ($id_producto > 0) {
                    $success = $this->cartModel->addItem($id_carrito, $id_producto, $cantidad);
                }
                break;
            case 'remove':
                if ($id_producto > 0) {
                    $success = $this->cartModel->removeItem($id_carrito, $id_producto);
                }
                break;
            case 'clear':
                $success = $this->cartModel->clearCart($id_carrito);
                break;
        }
        
        echo json_encode(['success' => $success]);
        exit;
    }
}