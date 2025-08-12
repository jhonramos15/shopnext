<?php
// En: src/controllers/vendedor/IncomeController.php

namespace App\Controllers\Vendedor;

use App\Models\Vendedor\IncomeModel;
use Exception;

class IncomeController {

    public function showIncomePage() {
        try {
            $incomeModel = new IncomeModel();
            
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $incomeModel->getVendedorInfo($id_usuario_session);

            if (!$vendedorInfo) {
                throw new Exception("Perfil de vendedor no encontrado para este usuario.");
            }
            
            $id_vendedor = $vendedorInfo['id_vendedor'];

            // Pedimos al modelo todos los datos que necesita la vista
            $stats = $incomeModel->getIncomeStats($id_vendedor);
            $sales = $incomeModel->getLatestSales($id_vendedor);

            // Empaquetamos todo en un solo array $data
            $data = [
                'nombre_vendedor' => $vendedorInfo['nombre'],
                'stats'           => $stats,
                'latest_sales'    => $sales
            ];
            
            // Y se lo pasamos a la vista
            require_once __DIR__ . '/../../../views/dashboard/vendedor/income.php';

        } catch (Exception $e) {
            die("Error en el controlador de ingresos: " . $e->getMessage());
        }
    }
}