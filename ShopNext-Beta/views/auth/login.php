<?php
// Carga el inicializador que contiene el autoloader y configuraciones globales
require_once __DIR__ . '/../../src/core/init.php';

// Usa nuestro nuevo guardia para proteger la página.
// Si el usuario ya está autenticado, lo redirige al dashboard.
use App\Guard\AuthGuard;
AuthGuard::redirectIfAuthenticated();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ShopNext</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/auth/sign-up.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>img/icon_principal.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="form-column">
            <div class="form-header">
                <a href="<?php echo BASE_URL; ?>index.php?action=home"><img src="<?php echo BASE_URL; ?>img/logo.svg" alt="ShopNext Logo" class="logo"></a>
                <div class="hamburger-menu">
                    <button id="hamburger-icon" class="hamburger-button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div id="dropdown-content" class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>index.php?action=home"><i class="fas fa-home"></i> <span>Inicio</span></a>
                        <a href="<?php echo BASE_URL; ?>index.php?action=products"><i class="fas fa-box-open"></i> <span>Productos</span></a>
                        <a href="<?php echo BASE_URL; ?>index.php?action=about-us"><i class="fas fa-info-circle"></i> <span>Acerca de</span></a>
                    </div>
                </div>
            </div>
            
            <div class="form-content">
                <h1>Iniciar Sesión</h1>
                <p class="subtitle">Inicia sesión para acceder a ShopNext</p>
                
                <form action="<?php echo BASE_URL; ?>index.php?action=login" method="POST">
                    <div class="form-group">
                        <input type="email" id="email" name="correo" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>

                    <div class="form-group password-wrapper">
                        <input type="password" id="contrasena" name="password" placeholder=" " required>
                        <label for="contrasena">Contraseña</label>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>

                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>

                <div class="divider">
                    <span>¿No tienes una cuenta?</span>
                </div>
                
                <a href="<?php echo BASE_URL; ?>index.php?action=signup" class="btn btn-secondary">
                    Crear una cuenta
                </a>
                
                <div class="divider">
                    <span>¿Olvidaste tu contraseña?</span>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?action=forgot-password" class="btn btn-secondary">
                    Recuperar Contraseña
                </a>
            </div>
        </div>

        <div class="image-column">
            <img src="<?php echo BASE_URL; ?>img/vista-frontal-del-concepto-de-compras-en-linea.jpg" alt="Shopping cart with bags">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo BASE_URL; ?>js/common/alertas.js"></script> 
    <script src="<?php echo BASE_URL; ?>js/auth/sign-up.js"></script> 
</body>
</html>