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
                    <a href="../../../controllers/logout.php"><i data-lucide="log-out"></i><span>Cerrar Sesión</span></a>
                </div>
            </div>
        </aside>

        <main class="main">
            <header class="header">
                <h1>Nuevo Producto</h1>
            </header>

            <div class="form-container">
                <form action="../../../controllers/uploads/uploadProduct.php" method="POST" enctype="multipart/form-data">
                    <div class="new-product-section">
                        <div class="details-column">
                            <div class="form-group">
                                <label for="titulo">TÍTULO <span class="required">*</span></label>
                                <input type="text" id="titulo" name="titulo" placeholder="Créale un nombre corto a tu producto." required>
                                <small class="form-hint">Entre 5 y 100 carácteres alfanuméricos</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="categoria">CATEGORÍA <span class="required">*</span></label>
                                <select id="categoria" name="categoria" required>
                                    <option value="" disabled selected>Añade tu producto a una categoría existente</option>
                                    <option value="Ropa Masculina">Ropa Masculina</option>
                                    <option value="Ropa Femenina">Ropa Femenina</option>
                                    <option value="Computadores">Computadores</option>
                                    <option value="Celulares">Celulares</option>
                                    <option value="Videojuegos">Videojuegos</option>
                                    <option value="Deportes">Deportes</option>
                                    <option value="Hogar & Belleza">Hogar & Belleza</option>
                                </select>
                                <a href="#" class="category-add"><i data-lucide="plus-circle"></i>Añadir categoría</a>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">DESCRIPCIÓN <span class="required">*</span></label>
                                <textarea id="descripcion" name="descripcion" rows="6" placeholder="Dale una descripción breve a tu producto." required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="precio">PRECIO <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i data-lucide="dollar-sign"></i>
                                        <input type="number" id="precio" name="precio" placeholder="Máximo 10 carácteres" step="0.01" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock">STOCK <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i data-lucide="boxes"></i>
                                        <input type="number" id="stock" name="stock" placeholder="Cantidad disponible" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="image-upload-section">
                            <label>IMAGEN <span class="required">*</span></label>
                            <small class="form-hint">Añade hasta 10 imágenes de tu producto.</small>
                            <div class="image-upload-box">
                                <input type="file" id="imagen" name="imagen[]" accept="image/png, image/jpeg, image/webp" required multiple>
                                <div class="upload-content">
                                    <i data-lucide="image-plus"></i>
                                    <p><span>Seleccionar archivo</span> o arrástralo aquí.</p>
                                    <small>PNG, JPG, WEBP de hasta 10MB</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="submit-button" type="submit" name="subir_producto">
                            <span>Subir Producto</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>lucide.createIcons();</script>
    <script src="../../../public/js/vendedor/subirProductos.js"></script>
</body>
</html>