<?php
// En: src/controllers/SellerDashboardController.php

namespace App\Controllers\Vendedor;

use App\Models\Vendedor\VendedorModel;
use Exception;

// ✅ El nombre de la clase ahora es específico
class DashboardController {

    public function showDashboard() { // Un nombre de método más genérico
        try {
            $dashboardModel = new VendedorModel();
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $dashboardModel->getVendedorInfo($id_usuario_session);

            if (!$vendedorInfo) {
                throw new Exception("Perfil de vendedor no encontrado.");
            }
            
            $id_vendedor = $vendedorInfo['id_vendedor'];
            $stats = $dashboardModel->getDashboardStats($id_vendedor);
            $pedidosRecientes = $dashboardModel->getPedidosRecientes($id_vendedor);

            $data = [
                'nombre_vendedor' => $vendedorInfo['nombre'],
                'ingresos_totales' => $stats['ingresos_totales'],
                'pedidos_realizados' => $stats['pedidos_realizados'],
                'nuevos_clientes' => $stats['nuevos_clientes'],
                'pedidos_recientes' => $pedidosRecientes
            ];
            
            require_once __DIR__ . '/../../../views/dashboard/vendedor-view.php';

        } catch (Exception $e) {
            die("Error en el controlador del dashboard: " . $e->getMessage());
        }
    }
}