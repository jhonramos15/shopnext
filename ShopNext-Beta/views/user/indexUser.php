<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/indexUser.css">
    <link rel="icon" href="img/icon_principal.ico" type="image/x-icon">
    <title>ShopNext | Inicio</title>
</head>
<body>
  
  <!-- Alerta Descuento -->
  <header>
    <div id="header-black">
      <p>Rebajas de Verano en Todos los Trajes de Baño y Envío Exprés Gratuito: ¡50 % de Descuento!</p>
      <h2>¡Compra Ahora!</h2>
      <select id="languages">
        <option value="Español:">Español:</option>
        <option value="English">English</option>
      </select>
    </div>

      <!-- Header Principal -->
      <div id="header-principal">
        <a href="index.html">
          <img src="../../public/img/logo.svg" alt="Logo ShopNext">
        </a>
        <div id="nav">
          <a href="indexUser.php">Inicio</a>
          <a href="#">Productos</a>
          <a href="pages/contact.php">Contacto</a>
        </div>

      <!-- Contenedor de íconos a la derecha -->
      <div class="iconos-derecha">
        <!-- Contenedor de la barra de búsqueda -->
        <div class="buscador">
          <input type="text" placeholder="¿Qué estás buscando?">
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass" style="color: #121212;"></i>
          </button>
        </div>

        <!-- Botón de Corazón -->
          <div class="heart">
            <button type="submit">
              <i class="fa-solid fa-heart" style="color: #121212;"></i>
            </button>
          </div>

        <!-- Botón de Carrito -->
          <div class="cart">
            <button type="submit">
              <i class="fa-solid fa-cart-shopping" style="color: #121212;"></i>
            </button>
          </div>

        <!-- Ícono de usuario -->
          <div class="user-menu-container">
            <i class="fas fa-user user-icon" style="color: #121212;" onclick="toggleDropdown()"></i>
            <div class="dropdown-content" id="dropdownMenu">
              <a href="../pages/account.php">Perfil</a>
              <a href="#">Pedidos 🚧</a>
              <a href="../../controllers/logout.php">Cerrar sesión</a>
            </div>
          </div>
    </div>

  </header>

          <!-- Categorías -->
    <section class="main-section">
      <div class="container">
        <div class="sidebar">
          <ul>
            <select id="clothes-woman">
              <option value="Ropa Femenina">Ropa Femenina</option>
              <option value="Camisas">Camisas</option>
              <option value="Pantalones">Pantalones</option>
              <option value="Calzado">Calzado</option>
              <option value="Gafas">Gafas</option>
              <option value="Ropa Interior">Ropa Interior</option>
            </select>
            <select id="clothes-men">
              <option value="Ropa Masculina">Ropa Masculina</option>
              <option value="Camisas">Camisas</option>
              <option value="Pantalones">Pantalones</option>
              <option value="Calzado">Calzado</option>
              <option value="Gafas">Gafas</option>
              <option value="Ropa Interior">Ropa Interior</option>
            </select>
            <select id="electronics">
              <option value="Electrónica">Electrónica</option>
              <option value="Computadores">Computadores</option>
              <option value="Celulares">Celulares</option>
              <option value="Televisores">Televisores</option>
              <option value="Equipo de Sonido">Equipo de Sonido</option>
              <option value="Todos">Todos</option>
            </select>
            <li>Hogar & Estilo de Vida</li>
            <li>Medicina</li>
            <li>Deportes</li>
            <li>Bebés y Juguetes</li>
            <li>Pets</li>
            <li>Salud & Belleza</li>
          </ul>
        </div>

          <!-- Carrusel -->
        <div class="separator"></div>
          <div class="carousel-container">
            <div class="carousel">
              <div class="slide">
                <div class="content">
                  <a href="#">Compra ahora →</a>
                </div>
                <img src="../../public/img/carousel/carrousel1.png" alt="iPhone 14">
              </div>
                <div class="slide">
                  <div class="content">
                    <a href="#">Compra Ahora →</a>
                  </div>
                  <img src="../../public/img/carousel/carrousel2.png" alt="HP">
                </div>
                <div class="slide">
                  <div class="content">
                    <a href="#">Compra Ahora →</a>
                </div>
                  <img src="../../public/img/carousel/carrousel3.png" alt="Nintendo Switch 2">
                </div>
                <div class="slide">
                  <div class="content">
                    <a href="#">Compra Ahora →</a>
                  </div>
                  <img src="../../public/img/carousel/carrousel4.png" alt="Honda">
                </div>
                <div class="slide">
                  <div class="content">
                    <a href="#">Compra Ahora →</a>
                  </div>
                  <img src="../../public/img/carousel/carrousel5.png" alt="Black Friday">
                </div>
            </div>
            <div class="dots"></div>
        </div>
      </div>
    </section>

          <!-- Flash Sales (Ventas Relámpago) -->
    <section>
      <div class="flash-sales">
        <div class="flash-header">
          <div class="title-container">
            <h2><span class="flash">Flash</span> <span class="sales">Sales</span></h2>
            <div class="countdown" id="countdown">
              <div><span id="days">00</span><span>:</span></div>
              <div><span id="hours">00</span><span>:</span></div>
              <div><span id="minutes">00</span><span>:</span></div>
              <div><span id="seconds">00</span></div>
            </div>
          </div>

          <!-- Botones de Flash Sales -->
          <div class="scroll-controls">
            <button class="scroll-btn" id="scrollLeftBtn">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="scroll-btn" id="scrollRightBtn">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>

        <!-- Productos del Flash Sales -->
        <div class="products-container">
          <div class="products" id="products">
            <div class="product">
              <div class="discount">-40%</div>
                <div class="icons">
                  <i class="fas fa-heart"></i>
                  <i class="fas fa-eye"></i>
                </div>
                <div class="product-image-wrapper">
                  <img src="../../public/img/flash-sales/asus .png" alt="Gamepad">
                  <button class="add-to-cart-btn">Añadir al carrito</button>
                </div>
                <p>Portatil ASUS A14</p>
                <p class="price">$120 <span class="old-price">$160</span></p>
                <p class="rating">★★★★★ (88)</p>
            </div>
            <div class="product">
              <div class="discount">-35%</div>
                <div class="icons">
                  <i class="fas fa-heart"></i>
                  <i class="fas fa-eye"></i>
                </div>
                <div class="product-image-wrapper">
                  <img src="../../public/img/flash-sales/keyboard.png" alt="AK-900 Keyboard">
                  <button class="add-to-cart-btn">Añadir al carrito</button>
                </div>
                <p>AK-900 Wired Keyboard</p>
                <p class="price">$960 <span class="old-price">$1160</span></p>
                <p class="rating">★★★★★ (75)</p>
            </div>
            <div class="product">
              <div class="discount">-30%</div>
              <div class="icons">
                <i class="fas fa-heart"></i>
                <i class="fas fa-eye"></i>
              </div>
              <div class="product-image-wrapper">
                <img src="../../public/img/flash-sales/monitor.png" alt="IPS Monitor">
                <button class="add-to-cart-btn">Añadir al carrito</button>
              </div>
              <p>IPS LCD Gaming Monitor</p>
              <p class="price">$370 <span class="old-price">$400</span></p>
              <p class="rating">★★★★★ (99)</p>
            </div>
            <div class="product">
              <div class="discount">-25%</div>
              <div class="icons">
                <i class="fas fa-heart"></i>
                <i class="fas fa-eye"></i>
              </div>
              <div class="product-image-wrapper">
                <img src="../../public/img/flash-sales/chair.png" alt="Comfort Chair">
                <button class="add-to-cart-btn">Añadir al carrito</button>
              </div>
              <p>S-Series Comfort Chair</p>
              <p class="price">$375 <span class="old-price">$400</span></p>
              <p class="rating">★★★★★ (99)</p>
            </div>
            <div class="product">
              <div class="discount">-20%</div>
              <div class="icons">
                <i class="fas fa-heart"></i>
                <i class="fas fa-eye"></i>
              </div>
              <div class="product-image-wrapper">
                <img src="../../public/img/flash-sales/headset.png" alt="Gaming Headset">
                <button class="add-to-cart-btn">Añadir al carrito</button>
              </div>
              <p>Gaming Headset X7</p>
              <p class="price">$120 <span class="old-price">$150</span></p>
              <p class="rating">★★★★☆ (64)</p>
            </div>
            <div class="product">
              <div class="discount">-15%</div>
              <div class="icons">
                <i class="fas fa-heart"></i>
                <i class="fas fa-eye"></i>
              </div>
                <div class="product-image-wrapper">
                <img src="../../public/img/flash-sales/mouse.png" alt="Wireless Mouse">
                <button class="add-to-cart-btn">Añadir al carrito</button>
              </div>
              <p>Wireless Mouse Pro</p>
              <p class="price">$85 <span class="old-price">$100</span></p>
              <p class="rating">★★★★★ (92)</p>
            </div>
          </div>
        </div>
      </div>
      <!-- Botón de Ver Todo-->
        <div class="view-all">
          <button>Ver Todo</button>
        </div>
    </section>

    <!-- Secciones Destacadas -->
    <section class="carousel-section">
      <div class="secciones-contenedor">
        <div class="secciones-titulo">
          <h2>Nuestras Secciones Destacadas</h2>
          <p>Explora nuestras mejores categorías</p>
        </div>
        <div class="secciones-carrusel">
          <!-- Sección 1: Teléfonos -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://hwrhsgeneralconsensus.com/wp-content/uploads/2018/11/B16B403C-B727-48CA-BF0B-7CFC47C455B2.jpeg');"></div>
                <div class="seccion-contenido">
                  <h3>Teléfonos</h3>
                  <p>Última tecnología en celulares</p>
                  <a href="#" class="seccion-boton">Ver más</a>
                </div>
            </div>

          <!-- Sección 2: Computadores -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://img.freepik.com/psd-gratis/venta-productos-viernes-negro-plantilla-diseno-publicaciones-redes-sociales_47987-24560.jpg');"></div>
                <div class="seccion-contenido">
                <h3>Computadores</h3>
                <p>Los mejores del mercado acá:</p>
                <a href="#" class="seccion-boton">Explorar</a>
              </div>
            </div>
                
          <!-- Sección 3: Hogar -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60');"></div>
                <div class="seccion-contenido">
                  <h3>Hogar y Estilo</h3>
                  <p>Diseño y confort para tu hogar</p>
                  <a href="#" class="seccion-boton">Descubrir</a>
              </div>
            </div>

          <!-- Sección 4: iPhone -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://images.unsplash.com/photo-1603921326210-6edd2d60ca68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60');"></div>
                <div class="seccion-contenido">
                <h3>iPhone 14 Series</h3>
                  <p>Hasta 10% de descuento</p>
                  <a href="#" class="seccion-boton">Comprar ahora</a>
                </div>
              </div>

          <!-- Sección 5: iPhone -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://images.unsplash.com/photo-1603921326210-6edd2d60ca68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60');"></div>
              <div class="seccion-contenido">
                <h3>iPhone 14 Series</h3>
                <p>Hasta 10% de descuento</p>
                <a href="#" class="seccion-boton">Comprar ahora</a>
              </div>
            </div>

          <!-- Sección 6: iPhone -->
            <div class="seccion-item">
              <div class="seccion-imagen" style="background-image: url('https://images.unsplash.com/photo-1603921326210-6edd2d60ca68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60');"></div>
              <div class="seccion-contenido">
                <h3>iPhone 14 Series</h3>
                <p>Hasta 10% de descuento</p>
                <a href="#" class="seccion-boton">Comprar ahora</a>
              </div>
            </div>
          </div>
            
            <!-- Controles en esquina superior derecha -->
            <div class="secciones-controles">
                <div class="control-seccion prev-seccion">←</div>
                <div class="control-seccion next-seccion">→</div>
            </div>
            
            <!-- Nuevo botón "Ver todos los productos" -->
            <a href="#" class="view-all-btn">Ver todos los productos</a>
      </div>
    </section>

    <section>
      <div class="section-header">
        <div class="title">
          <div class="badge">Este mes</div>
          Productos más vendidos
        </div>
        <button class="view-all-btn2">Ver todos</button>
      </div>
    
      <div class="product-grid">
        <div class="product-card">
          <div class="product-image-wrapper">
            <div class="icons">
              <a href="#"><i class="fas fa-heart"></i></a>
              <a href="#"><i class="fas fa-eye"></i></a>
            </div>
            <img src="../../public/img/coat.png" alt="The north coat">
            <button class="add-to-cart-btn">Añadir al carrito</button>
          </div>
          <div class="product-details">
            <div class="product-title">The North Coat</div>
            <div>
              <span class="price">$260</span>
              <span class="old-price">$360</span>
            </div>
            <div class="stars">★★★★★ <span class="rating-count">(65)</span></div>
          </div>
        </div>
    
        <div class="product-card">
          <div class="icons">
            <a href="#"><i class="fas fa-heart"></i></a>
            <a href="#"><i class="fas fa-eye"></i></a>
          </div>
          <div class="product-image-wrapper">
            <img src="../../public/img/gucci.png" alt="Gucci duffle bag">
            <button class="add-to-cart-btn">Añadir al carrito</button>
          </div>
          <div class="product-details">
            <div class="product-title">Gucci Bolso</div>
            <div>
              <span class="price">$960</span>
              <span class="old-price">$1160</span>
            </div>
            <div class="stars">★★★★★ <span class="rating-count">(65)</span></div>
          </div>
        </div>
    
        <div class="product-card">
          <div class="icons">
            <a href="#"><i class="fas fa-heart"></i></a>
            <a href="#"><i class="fas fa-eye"></i></a>
          </div>
          <div class="product-image-wrapper">
            <img src="../../public/img/rgb-al.png" alt="RGB liquid CPU Cooler">
            <button class="add-to-cart-btn">Añadir al carrito</button>
          </div>
          <div class="product-details">
            <div class="product-title">Refrigeración Líquida RGB</div>
            <div>
              <span class="price">$160</span>
              <span class="old-price">$170</span>
            </div>
            <div class="stars">★★★★★ <span class="rating-count">(65)</span></div>
          </div>
        </div>
    
        <div class="product-card">
          <div class="icons">
            <a href="#"><i class="fas fa-heart"></i></a>
            <a href="#"><i class="fas fa-eye"></i></a>
          </div>
          <div class="product-image-wrapper">
            <img src="../../public/img/bookself.png" alt="Small BookSelf">
            <button class="add-to-cart-btn">Añadir al carrito</button>
          </div>
          <div class="product-details">
            <div class="product-title">Small BookSelf</div>
            <div>
              <span class="price">$360</span>
            </div>
            <div class="stars">★★★★★ <span class="rating-count">(65)</span></div>
          </div>
        </div>
      </div>
      
      <div class="container">
        <div class="left-section">
          <div class="category">Categorías</div>
          <div class="headline">Enhance Your<br>Experiencia Musical</div>
          <div class="countdown" id="countdown">
          </div>
          <button class="buy-button">¡Compra ahora!</button>
        </div>
        <div class="right-section">
          <img src="../../public/img/Captura de pantalla 2025-04-22 130756.png" alt="JBL Speaker">
        </div>
      </div>

    </section>

    <section>
      <div class="section-header">
        <div class="title-buttons">
          <div class="badge2">Este Mes</div>
          <div class="title2">Productos Más Vendidos</div>
        </div>
        <div class="arrows-wrapper">
          <button class="arrow-btn arrow-left" onclick="slide(-1)">&#10094;</button>
          <button class="arrow-btn arrow-right" onclick="slide(1)">&#10095;</button>
        </div>
      </div>
    
      <div class="products-section">
  
        <div class="slider-container" id="slider">
          <div class="slider">
            <div class="products-grid">
              <!-- Fila 1 -->
              <!-- Reemplazar con productos reales -->
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/food-animal.png" alt="Animal Food">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Comida de Animales</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/camera.png" alt="Camera">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Camera</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/ideapad-gaming-3i-01-500x500 1.png" alt="Ideapad Gaming">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">ASUS FHD Gaming Laptop</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/curology.png" alt="Curology Product Set ">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Conjunto de productos Curology</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
            </div>
  
  
            <div class="products-grid">
              <!-- Fila 2 -->
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/mini-car.png" alt="Car Electric">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Carro Electríco de Niños</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
              
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/shoes-football.png" alt="Shoes Football">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Zapatos de Fútbol</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/control-gamin.png" alt="Control Gaming">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Control Gaming USB Gamepad</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/clothes-men.png" alt="Jacket Men">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">Chaqueta Verde Hombre</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
            </div>
          </div>
          <div class="slider">
            <div class="products-grid">
              <!-- Sección 2 Fila 1 -->
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
              
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
            </div>
            <div class="products-grid">
              <!-- Sección 2 Fila 2 -->
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
  
              <div class="product-card">
                <div class="products-image-wrapper">
                  <div class="icons">
                    <a href="#"><i class="fas fa-heart"></i></a>
                    <a href="#"><i class="fas fa-eye"></i></a>
                  </div>
                  <img src="../../public/img/bookself.png" alt="The north coat">
                  <button class="add-to-cart-btns">Añadir al carrito</button>
                </div>
                <div class="products-details">
                  <div class="products-title">The north coat</div>
                  <div>
                    <span class="prices">$260</span>
                    <span class="old-prices">$360</span>
                  </div>
                  <div class="stars2">★★★★★ <span class="rating-count">(65)</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <button class="view-all-btn3">Ver todos</button>
      </div>
    </section>

    <section>

      <!-- Sección en proceso  -->

     <!-- <div class="exclusive-products">
        <div class="title-buttons">
          <div class="badge2">Destacados</div>
          <div class="title2">Próximamente</div>
        </div>
        <div class="ps5">
          <img src="img/ps5-slim.jpg" alt="PS5 Slim">
          <h2>Play Station 5</h2>
          <h3>Versiones en negro y blanco de la PS5.</h3>
          <a href="html/404.html">¡Compra Ahora!</a>
        </div>
        <div class="women">
          <img src="img/women-pub.png" alt="Women Pub">
          <h2>Colección Femenina</h2>
          <h3>La mejor moda femenina para tí.</h3>
          <a href="html/404.html">¡Compra Ahora!</a>
        </div>
        <div class="speakers">
          <img src="img/speaker-pub.png" alt="Speaker">
          <h2>Altavoces</h2>
          <h3>Los mejores altavoces al alcance.</h3>
          <a href="html/404.html">¡Compra Ahora!</a>
        </div>
        <div class="gucci-pub">
          <img src="img/gucci-pub.png" alt="Gucci Publi">
          <h2>Perfume</h2>
          <h3>Fragancia intensa de parte de Gucci.</h3>
          <a href="html/404.html">¡Compra Ahora!</a>
        </div>
       </div> -->

  <div class="benefits-container">

    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../public/img/security.png" alt="Security" />
      </div>
      <h3>ENVÍO RÁPIDO Y GRATUITO</h3>
      <p>Envíos con descuento desde los 140.000 COP</p>
    </div>
  
    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../public/img/services.png" alt="Customer Support" />
      </div>
      <h3>ATENCIÓN AL CLIENTE 24/7</h3>
      <p>Soporte amigable 24/7</p>
    </div>
  
    <div class="benefit-box">
      <div class="benefit-icon">
        <img src="../../public/img/support.png" alt="Guarantee" />
      </div>
      <h3>GARANTÍA DE DEVOLUCIÓN</h3>
      <p>30 días para tu reembolso</p>
    </div>
  </div>


    </section>

    <footer>
      <div class="footer-section">
        <img src="../../public/img/logo-positivo.png" alt="ShopNexs Logo" class="footer-logo"> <!-- Agregar el logo correspondiente-->
      </div>
  
      <div class="send-message">
        <input type="text" placeholder="Envía un correo">
        <button type="submit">
          <i class="fa-solid fa-paper-plane" style="color: #0c0c0c;"></i>
        </button>
      </div>
    
      <div class="footer-section">
        <h3>Información</h3>
        <ul>
          <li><a href="/html/about-us.html">Acerca de</a></li>
          <li><a href="/html/contact.html">Contacto</a></li>
          <li><a href="/html/sign-up.html">Regístrate</a></li>
        </ul>
      </div>
    
      <div class="footer-section">
        <h3>Soporte</h3>
        <ul>
          <li><a>soporteshopnexts@gmail.com</a></li>
          <li><a>Calle 133 # 123 - 34 Piso 12</a></li>
          <li><a>+57 343 948 9283</a></li>
        </ul>
      </div>
    
      <div class="footer-section">
        <h3>Contacto</h3>
        <ul>
          <li><a>Redes Sociales</a></li>
  
          <img src="../../public/img/Icon-Twitter.png" alt="Icon Twitter">
          <img src="../../public/img/icon-instagram.png" alt="Icon Instagram">
          <img src="../../public/img/Icon-Linkedin.png" alt="Icon LinkedIn">
        </ul>
      </div>
    </footer>
 <script src="../../public/js/index.js"></script>
</body>
</html>