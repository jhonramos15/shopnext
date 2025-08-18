// En: public/js/user/favorite-actions.js

document.addEventListener('DOMContentLoaded', () => {

    // --- LÓGICA PARA AÑADIR/QUITAR UN SOLO FAVORITO ---
    document.body.addEventListener('click', function(event) {
        const favoriteButton = event.target.closest('.favorite-btn, .remove-from-wishlist');
        if (favoriteButton) {
            handleToggleFavorite(favoriteButton);
        }
    });

    // --- LÓGICA PARA MOVER TODO AL CARRITO ---
    const moveAllButton = document.querySelector('.move-all-btn');
    if (moveAllButton) {
        moveAllButton.addEventListener('click', function () {
            Swal.fire({
                title: '¿Mover todo al carrito?',
                text: "Todos los productos de tu lista se añadirán al carrito.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, mover todo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    moveAllToCart();
                }
            });
        });
    }
});

/**
 * Maneja la lógica de agregar/quitar un producto de favoritos via AJAX.
 */
function handleToggleFavorite(button) {
    const idProducto = button.dataset.id;
    if (!idProducto) return;

    const formData = new FormData();
    formData.append('id_producto', idProducto);

    fetch(`${App.baseUrl}index.php?action=toggle-favorite`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFavoriteUI(button, data.action, idProducto);
        } else {
            Swal.fire('Error', data.message || 'No se pudo procesar la solicitud.', 'error');
        }
    })
    .catch(error => console.error('Error en fetch:', error));
}

/**
 * Maneja la lógica para mover todos los favoritos al carrito.
 */
function moveAllToCart() {
    fetch(`${App.baseUrl}index.php?action=move-favorites-to-cart`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                // Redirige al carrito para ver los productos añadidos
                window.location.href = `${App.baseUrl}index.php?action=cart`;
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => console.error('Error en fetch:', error));
}


/**
 * Actualiza la interfaz de usuario después de una acción de favorito.
 */
function updateFavoriteUI(button, action, idProducto) {
    const heartIcon = button.querySelector('i.fa-heart');

    if (action === 'added') {
        if (heartIcon) {
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid'); // Corazón lleno
        }
        Swal.fire({ title: '¡Añadido!', text: 'El producto se agregó a tus favoritos.', icon: 'success', timer: 1500 });
    } else { // 'removed'
        if (heartIcon) {
            heartIcon.classList.remove('fa-solid');
            heartIcon.classList.add('fa-regular'); // Corazón vacío
        }
        
        // Si estamos en la página de favoritos, la tarjeta del producto se elimina visualmente.
        const productCardOnWishlist = document.getElementById(`product-${idProducto}`);
        if (productCardOnWishlist) {
            productCardOnWishlist.style.transition = 'opacity 0.5s';
            productCardOnWishlist.style.opacity = '0';
            setTimeout(() => productCardOnWishlist.remove(), 500);
        }
    }
}