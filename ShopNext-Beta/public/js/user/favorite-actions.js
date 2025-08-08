/**
 * favoriteActions.js
 * * Gestiona todas las interacciones del usuario con la lista de favoritos,
 * incluyendo agregar, eliminar y actualizar la interfaz.
 * Utiliza delegación de eventos para optimizar el rendimiento.
 */
document.addEventListener('DOMContentLoaded', () => {

    // Usamos un solo 'oyente' en el cuerpo del documento para manejar todos los clics.
    document.body.addEventListener('click', function(event) {

        // Identificamos si el clic fue en un botón para agregar/quitar de favoritos.
        const favoriteButton = event.target.closest('.favorite-btn');
        if (favoriteButton) {
            handleToggleFavorite(favoriteButton);
            return; // Detenemos la ejecución para no hacer más comprobaciones.
        }

        // Identificamos si el clic fue en un botón para eliminar desde la lista de deseos.
        const removeButton = event.target.closest('.remove-from-wishlist');
        if (removeButton) {
            handleToggleFavorite(removeButton); // Reutilizamos la misma función.
        }
    });

});

/**
 * Función central que maneja la lógica de agregar/quitar un producto de favoritos.
 * @param {HTMLElement} button - El botón que fue presionado.
 */
function handleToggleFavorite(button) {
    const idProducto = button.dataset.id;
    if (!idProducto) return; // Salir si el botón no tiene un ID de producto.

    // Preparamos los datos para enviar al servidor.
    const formData = new FormData();
    formData.append('id_producto', idProducto);

    // Llamamos al controlador de PHP usando fetch (AJAX).
    fetch('index.php?action=toggle-favorite', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Hubo un problema con la respuesta del servidor.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // El servidor procesó la petición correctamente.
            updateFavoriteUI(button, data.action, idProducto);
        } else {
            // El servidor devolvió un error (ej. el usuario no está logueado).
            Swal.fire('Error', data.error || 'No se pudo procesar la solicitud.', 'error');
        }
    })
    .catch(error => {
        console.error('Error en la petición fetch:', error);
        Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}

/**
 * Actualiza la interfaz de usuario después de una acción de favorito.
 * @param {HTMLElement} button - El botón que originó la acción.
 * @param {string} action - La acción realizada ('added' o 'removed').
 * @param {string} idProducto - El ID del producto afectado.
 */
function updateFavoriteUI(button, action, idProducto) {
    const heartIcon = button.querySelector('i'); // Buscamos el ícono del corazón.

    if (action === 'added') {
        // Mostramos la alerta de éxito.
        Swal.fire({
            title: '¡Añadido!',
            text: 'El producto se agregó a tu lista de deseos.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            width: '300px'
        });

        // Cambiamos el ícono a un corazón relleno.
        if (heartIcon) {
            heartIcon.classList.remove('far'); // Quita el corazón vacío.
            heartIcon.classList.add('fas');   // Pone el corazón lleno.
        }

    } else { // 'removed'
        // Cambiamos el ícono a un corazón vacío.
        if (heartIcon) {
            heartIcon.classList.remove('fas');
            heartIcon.classList.add('far');
        }

        // Si estamos en la página de favoritos, la tarjeta del producto se elimina.
        // El botón `.remove-from-wishlist` no tiene ícono, pero su acción es 'removed'.
        const productCardOnWishlist = document.getElementById(`product-${idProducto}`);
        if (productCardOnWishlist) {
            // Animación de salida suave.
            productCardOnWishlist.style.transition = 'opacity 0.5s';
            productCardOnWishlist.style.opacity = '0';
            setTimeout(() => productCardOnWishlist.remove(), 500);
        }
    }
}