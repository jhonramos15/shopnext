document.addEventListener('DOMContentLoaded', () => {

    const productsGrid = document.querySelector('.products-grid');
    const wishlistTitle = document.getElementById('wishlist-title');
    const headerCounter = document.getElementById('wishlist-header-counter');
    const moveAllBtn = document.querySelector('.wishlist-section .btn-outline');

    // Función para dar formato de moneda a los precios
    function formatearPrecio(valor) {
        const numero = parseInt(valor);
        // 'es-ES' usa punto para miles, ajusta si necesitas otro formato.
        return '$' + numero.toLocaleString('es-ES');
    }

    // Función para actualizar los contadores en el título y en el header
    async function actualizarContadores() {
        try {
            const res = await fetch('../php/wishlist_api.php?action=contar');
            const data = await res.json();
            const total = data.total;

            if (wishlistTitle) {
                wishlistTitle.textContent = `Wishlist (${total})`;
            }
            if (headerCounter) {
                headerCounter.textContent = total;
            }
        } catch (error) {
            console.error("Error al actualizar contadores:", error);
        }
    }

    // Función principal para cargar y mostrar los productos de la wishlist
    async function cargarWishlist() {
        try {
            const res = await fetch('../php/wishlist_api.php?action=listar');
            const data = await res.json();

            productsGrid.innerHTML = ''; // Limpiar la grilla antes de añadir nuevos productos
            await actualizarContadores(); // Actualizar los números en la UI

            if (data.length === 0) {
                productsGrid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1;">Tu lista de deseos está vacía.</p>';
                moveAllBtn.style.display = 'none'; // Ocultar botón si no hay productos
                return;
            }
            
            moveAllBtn.style.display = 'inline-block'; // Mostrar el botón si hay productos

            data.forEach(item => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <div class="card-header">
                        <span></span> <button class="icon-btn remove-btn" data-id="${item.id}" title="Eliminar de la lista">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    <div class="product-image">
                        <img src="../${item.imagen}" alt="${item.producto}">
                        <button class="add-to-cart-btn move-one-btn" data-id="${item.id}">
                            <i class="fa-solid fa-shopping-cart"></i> Add To Cart
                        </button>
                    </div>
                    <div class="product-info">
                        <p class="product-title">${item.producto}</p>
                        <p class="product-price">${formatearPrecio(item.precio)}</p>
                    </div>
                `;
                productsGrid.appendChild(productCard);
            });
        } catch (error) {
            console.error("Error al cargar la wishlist:", error);
        }
    }

    // --- MANEJO DE EVENTOS ---

    // Usamos delegación de eventos en la grilla para manejar los clics
    productsGrid.addEventListener('click', async (e) => {
        const button = e.target.closest('button');
        if (!button) return; // Si no se hizo clic en un botón, no hacer nada

        const id = button.dataset.id;

        // Si es el botón de eliminar
        if (button.classList.contains('remove-btn')) {
            await fetch('../php/wishlist_api.php?action=eliminar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            });
            cargarWishlist(); // Recargar la lista para reflejar el cambio
        }

        // Si es el botón de mover al carrito
        if (button.classList.contains('move-one-btn')) {
            await fetch('../php/wishlist_api.php?action=mover_uno', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            });
            alert('Producto movido al carrito.');
            cargarWishlist(); // Recargar la lista
        }
    });

    // Evento para el botón "Move All To Bag"
    moveAllBtn.addEventListener('click', async () => {
        if (confirm('¿Estás seguro de que quieres mover todos los productos al carrito?')) {
            await fetch('../php/wishlist_api.php?action=mover_todo', { method: 'POST' });
            alert('Todos los productos han sido movidos al carrito.');
            cargarWishlist(); // Recargar la lista, que ahora estará vacía
        }
    });

    // Carga inicial de la wishlist cuando el DOM está listo
    cargarWishlist();
});