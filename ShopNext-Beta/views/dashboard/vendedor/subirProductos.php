<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../auth/login.html");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 300;

// Verificar si existe el tiempo de última actividad
if (isset($_SESSION['last_activity'])) {
    $tiempo_inactivo = time() - $_SESSION['last_activity'];

    if ($tiempo_inactivo > $inactividad) {
        // Cierra la sesión si pasó el tiempo
        session_unset();
        session_destroy();
        header("Location: ../auth/login.php?mensaje=sesion_expirada");
        exit;
    } else {
        $_SESSION['last_activity'] = time(); // ✅ Refresca el tiempo de actividad
    }
} else {
    $_SESSION['last_activity'] = time(); // ✅ Inicializa el tiempo de actividad si no existía
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/vendedor/subirProductos.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <title>Dashboard - Nuevo Producto</title>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img"/>
            </div>
            <ul class="menu">
           <li class="active"><a href="../vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
           <li><a href="productos.php"><i data-lucide="package"></i><span>Productos</span></a></li>
           <li><a href="pedidos.php"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
           <li><a href="subirProductos.php"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
           <li><a href="ingresos.php"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
            </ul>
            <div class="user-profile-container">
                <div class="user" id="userProfileBtn">
                    <div class="user-info">
                        <p>Brayan</p>
                        <small>Administrador</small>
                    </div>
                    <i data-lucide="chevron-down" class="profile-arrow"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdownMenu">
                    <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
                    <a href="#configuracion"><i data-lucide="settings"></i><span>Configuración</span></a>
                    <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
                </div>
            </div>
        </aside>

        <main class="main">
            <header class="header">
                <h1>Nuevo Producto</h1>
            </header>

            <form action="../../../controllers/uploads/uploadProduct.php" method="POST" enctype="multipart/form-data">
                <section class="new-product-section">
                    <div class="details-column">
                        <div class="form-group">
                            <label for="titulo">Título <span class="required">*</span></label>
                            <input type="text" id="titulo" name="titulo" placeholder="Crea un nombre corto a tu producto.">
                            <small class="form-hint">Entre 5 y 100 caracteres alfanuméricos</small>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría <span class="required">*</span></label>
                            <div class="input-icon">
                                <i data-lucide="tag"></i>
                                <input type="text" id="categoria" name="categoria" placeholder="Añade tu producto a una categoría existente.">
                            </div>
                            <div class="category-add">
                                <i data-lucide="plus-circle"></i>
                                <span>Añadir categoría principal</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción <span class="required">*</span></label>
                            <textarea id="descripcion" name="descripcion" rows="5" placeholder="Dale una descripción breve a tu producto."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio <span class="required">*</span></label>
                            <div class="input-icon">
                                <i data-lucide="dollar-sign"></i>
                                <input type="number" id="precio" name="precio" placeholder="Ponle un precio a tu producto.">
                            </div>
                        </div>
                    </div>

                    <div class="media-column">
                        <div class="form-group">
                            <label>Imagen <span class="required">*</span></label>
                            <p class="form-hint">Añade hasta 10 imágenes de tu producto.</p>
                            <div class="image-upload-section">
                                <div class="image-preview-main">
                                    <i data-lucide="image" class="placeholder-icon"></i>
                                </div>
                                <div class="image-preview-list">
                                    <label for="imageUpload" class="add-image-button">
                                        <i data-lucide="plus"></i>
                                        <input type="file" id="imageUpload" name="imagen[]" hidden multiple>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <div class="form-actions">
                    <button class="submit-button" type="submit" name="subir_producto">Subir Producto</button>
                </div>
            </form>
        </main>
    </div>
    <script>
        lucide.createIcons();
    </script>
    <script src="../../../public/js/vendedor/subirProductos.js"></script>
</body>
</html>