<?php
// En src/controllers/auth/RegistroController.php

namespace App\Controllers\Auth;

use App\Models\Usuario;
use Exception;

class RegistroController
{
    private $usuarioModel;

    public function __construct()
    {
        // El autoloader se encarga de encontrar el archivo Usuario.php
        $this->usuarioModel = new Usuario();
    }

    public function showRegistrationForm()
    {
        // Carga la vista del formulario de registro
        require_once __DIR__ . '/../../../views/auth/sign-up.html';
    }

    public function handleRegistration()
{
    // Indicamos que vamos a devolver JSON
    header('Content-Type: application/json');

    // MODO DETECTIVE: En lugar de procesar, simplemente capturamos
    // lo que sea que llegue en $_POST y lo devolvemos.
    $datos_recibidos = $_POST;

    // Enviamos una respuesta que SIEMPRE será exitosa (código 200)
    // para que el JavaScript la pueda leer fácilmente en la consola.
    echo json_encode([
        'success' => true,
        'message' => 'Datos recibidos por el servidor.',
        'data' => $datos_recibidos // ¡Aquí está la información espía!
    ]);
    
    exit; // Detenemos el script
}
}