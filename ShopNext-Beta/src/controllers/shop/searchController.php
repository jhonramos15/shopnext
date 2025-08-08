<?php
namespace App\Controllers\Shop;

use App\Models\Shop\ProductsModel;

class SearchController {
    private $productsModel;

    public function __construct() {
        $this->productsModel = new ProductsModel();
    }

    /**
     * Maneja la petición de búsqueda y devuelve los resultados en JSON.
     */
    public function handleSearch() {
        // Obtenemos el término de búsqueda de la URL (?term=...).
        $searchTerm = $_GET['term'] ?? '';

        // Nos aseguramos de que haya algo que buscar.
        if (empty($searchTerm)) {
            echo json_encode([]); // Devolvemos un arreglo vacío si no hay término.
            return;
        }

        // Usamos el modelo para obtener los productos.
        $results = $this->productsModel->searchByName($searchTerm);

        // Configuramos la cabecera para indicar que la respuesta es JSON.
        header('Content-Type: application/json');

        // Convertimos el arreglo de resultados a formato JSON y lo imprimimos.
        echo json_encode($results);
    }
}