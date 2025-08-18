<?php
// En: src/controllers/shop/SearchController.php

namespace App\Controllers\Shop;

use App\Models\ProductsModel;

class SearchController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductsModel();
    }

    public function handleSearch() {
        header('Content-Type: application/json');
        
        // Obtenemos el término de búsqueda de la URL
        $searchTerm = $_GET['term'] ?? '';

        // --- PUNTO DE DEPURACIÓN ---
        // Descomenta la siguiente línea para ver qué está recibiendo el backend.
        // die(json_encode(['termino_recibido' => $searchTerm]));

        if (empty($searchTerm)) {
            echo json_encode([]);
            exit;
        }

        // Le pedimos al modelo que busque los productos
        $results = $this->productModel->searchByName($searchTerm);
        
        // Devolvemos los resultados
        echo json_encode($results);
    }
}