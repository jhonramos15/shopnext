<?php
// Incluimos el modelo que vamos a utilizar
require_once __DIR__ . '/../../models/productsModel.php';

class IndexController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Comprobar si el usuario ha iniciado sesión para pasárselo a la vista
        $usuario_logueado = isset($_SESSION['id_usuario']);

        $productsModel = new ProductsModel();

        // Obtenemos los últimos productos PUBLICADOS
        $latestProducts = $productsModel->getLatestProducts(8);
        // Obtenemos los MÁS VENDIDOS
        $bestSellingProducts = $productsModel->getBestSellingProducts(4);

        // Cargamos la vista principal y le pasamos los datos
        // La vista se encargará únicamente de mostrar esta información.
        require_once __DIR__ . '/../../../views/pages/home.php';
    }
}

// Lógica para ejecutar el controlador (esto puede ir en tu router principal)
$controller = new IndexController();
$controller->index();

?>