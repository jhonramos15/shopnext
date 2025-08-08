<?php
// src/controllers/user/FavoritosController.php

namespace App\Controllers\User;

use App\Models\FavoritosModel;
use App\Core\SessionManager;

class FavoritosController {

    /**
     * Muestra la página de "Mis Favoritos" con los productos del usuario.
     */
    public function show() {
        // Guardián: si no está logueado, al login.
        if (!SessionManager::isLoggedIn() || SessionManager::get('rol') !== 'cliente') {
            header('Location: index.php?action=login');
            exit;
        }

        $id_usuario = SessionManager::get('id_usuario');
        
        // 1. Pedimos al modelo que nos traiga los favoritos
        $favoritosModel = new FavoritosModel();
        $lista_favoritos = $favoritosModel->getFavoritesByUserId($id_usuario);

        // 2. Preparamos los datos para la vista
        $data = [
            'titulo_pagina' => 'Mis Favoritos',
            'favoritos' => $lista_favoritos
        ];

        // 3. Cargamos la vista y le pasamos los datos
        require_once __DIR__ . '/../../../views/user/pages/favoritos.php';
    }

    /**
     * Agrega o quita un producto de favoritos (función para AJAX).
     * Este método ya lo tenías bien, solo nos aseguramos de que use el nuevo modelo.
     */
    public function toggleFavorite() {
        // Este código tuyo ya está bien estructurado y funcionará con el nuevo modelo
        header('Content-Type: application/json');

        if (!SessionManager::isLoggedIn() || SessionManager::get('rol') !== 'cliente') {
            echo json_encode(['success' => false, 'error' => 'login_required']);
            exit;
        }

        if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto'])) {
            echo json_encode(['success' => false, 'error' => 'ID de producto inválido.']);
            exit;
        }

        try {
            $id_usuario = SessionManager::get('id_usuario');
            $id_producto = (int)$_POST['id_producto'];

            $favoritesModel = new FavoritosModel();
            $action = $favoritesModel->toggle($id_usuario, $id_producto);

            echo json_encode(['success' => true, 'action' => $action]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}