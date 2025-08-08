<?php
namespace App\Controllers\Shop;

use App\Core\SessionManager;

/**
 * Controlador para manejar las páginas estáticas como "Acerca de" y "Contacto".
 */
class PagesController {

    /**
     * Muestra la página "Acerca de Nosotros".
     */
    public function about() {
        // Preparamos el arreglo de datos que se enviará a la vista.
        $data = [
            'titulo_pagina' => 'Acerca de Nosotros',
            'usuario_logueado' => SessionManager::isLoggedIn()
        ];

        // --- CORRECCIÓN AQUÍ ---
        // En lugar de llamar a una función 'view()', cargamos el archivo de la vista directamente.
        // La variable $data estará disponible dentro del archivo que incluyamos.
        require_once __DIR__ . '/../../../views/pages/about-us.php';
    }

    /**
     * Muestra la página de "Contacto".
     */
    public function contact() {
        $data = [
            'titulo_pagina' => 'Contacto',
            'usuario_logueado' => SessionManager::isLoggedIn()
        ];

        // Hacemos lo mismo para la vista de contacto.
        require_once __DIR__ . '/../../../../views/pages/contact.php';
    }
}