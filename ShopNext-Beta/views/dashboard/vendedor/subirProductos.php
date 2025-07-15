<?php
session_start();

// Guardián de seguridad
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../auth/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

$nombre_vendedor = $_SESSION['nombre_vendedor'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Subir Producto</title>
    <link rel="stylesheet" href="../../../public/css/vendedor/subirProductos.css">
    <link rel="icon" href="../../../public/img/icon_principal.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="../../../public/img/logo.svg" alt="Logo" class="logo-img"/>
            </div>
            <ul class="menu">
               <li><a href="../vendedorView.php"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a></li>
               <li><a href="productos.php"><i data-lucide="package"></i><span>Productos</span></a></li>
               <li><a href="pedidos.php"><i data-lucide="shopping-cart"></i><span>Pedidos</span></a></li>
               <li class="active"><a href="subirProductos.php"><i data-lucide="upload-cloud"></i><span>Subir Producto</span></a></li>
               <li><a href="ingresos.php"><i data-lucide="dollar-sign"></i><span>Ingresos</span></a></li>
            </ul>
            <div class="user-profile-container">
                <div class="user" id="userProfileBtn">
                    <img src="https://i.pravatar.cc/40" alt="user" />
                    <div class="user-info">
                        <p><?php echo htmlspecialchars($nombre_vendedor); ?></p>
                        <small>Vendedor</small>
                    </div>
                    <i data-lucide="chevron-down" class="profile-arrow"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdownMenu">
                    <a href="#perfil"><i data-lucide="user"></i><span>Mi Perfil</span></a>
                    <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
                </div>
            </div>
        </aside>

        <main class="main">
            <header class="header">
                <h1>Sube un Nuevo Producto</h1>
            </header>

            <div class="form-container">
                <form action="../../../controllers/uploads/uploadProduct.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo">Título del Producto <span class="required">*</span></label>
                        <input type="text" id="titulo" name="titulo" placeholder="Ej: Camisa de Algodón Slim Fit" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Categoría <span class="required">*</span></label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <option value="Ropa Masculina">Ropa Masculina</option>
                            <option value="Ropa Femenina">Ropa Femenina</option>
                            <option value="Computadores">Computadores</option>
                            <option value="Celulares">Celulares</option>
                            <option value="Videojuegos">Videojuegos</option>
                            <option value="Deportes">Deportes</option>
                            <option value="Hogar & Belleza">Hogar & Belleza</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción <span class="required">*</span></label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe las características principales de tu producto." required></textarea>
                    </div>

                    <div class="price-stock-container">
                        <div class="form-group">
                            <label for="precio">Precio <span class="required">*</span></label>
                            <input type="number" id="precio" name="precio" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock <span class="required">*</span></label>
                            <input type="number" id="stock" name="stock" placeholder="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Imágenes del Producto <span class="required">*</span></label>
                        <div class="image-upload-box">
                            <input type="file" id="imagen" name="imagen[]" accept="image/png, image/jpeg, image/webp" required>
                            <div class="upload-content">
                                <i data-lucide="image-plus"></i>
                                <p><span>Seleccionar archivo</span> o arrástralo aquí.</p>
                                <small>PNG, JPG, WEBP de hasta 10MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="submit-button" type="submit" name="subir_producto">Subir Producto</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>lucide.createIcons();</script>
    <script src="../../../public/js/vendedor/subirProductos.js"></script>
</body>
</html>