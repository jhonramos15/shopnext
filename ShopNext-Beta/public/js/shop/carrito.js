/**
 * ======================================================================
 * ARCHIVO: carrito.js (Versión Final y Refactorizada)
 * ======================================================================
 */

// NOTA: Esta variable la define la vista PHP (carrito.php)
// const CART_API_URL = 'index.php?action=cart-api';

/**
 * Función central para todas las interacciones con la API del carrito.
 */
function callCartApi(action, data = {}) {
    const formData = new FormData();
    formData.append('action', action);

    for (const key in data) {
        formData.append(key, data[key]);
    }

    return fetch(CART_API_URL, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return response.json();
    })
    .catch(error => {
        console.error('Error en la API del carrito:', error);
        Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}

/**
 * Función global para añadir un producto al carrito.
 */
function agregarAlCarrito(productoId) {
    const cantidadInput = document.getElementById('quantity-input');
    const cantidad = cantidadInput ? cantidadInput.value : 1;

    callCartApi('add', { id_producto: productoId, cantidad: cantidad })
        .then(response => {
            if (response && response.success) {
                Swal.fire('¡Añadido!', 'El producto se ha añadido a tu carrito.', 'success');
            } else {
                const message = response ? response.message : 'No se pudo añadir el producto.';
                Swal.fire('Error', message, 'error');
            }
        });
}

/**
 * Lógica específica para la página del carrito.
 */
document.addEventListener('DOMContentLoaded', function () {
    const cartContainer = document.querySelector('.cart-container');
    if (!cartContainer) return; // Si no es la página del carrito, no seguir.

    // Event listener para los botones de eliminar y vaciar.
    cartContainer.addEventListener('click', (event) => {
        const target = event.target;
        const cartItem = target.closest('.cart-item');

        // Botón para eliminar un solo producto.
        if (target.closest('.remove-item-btn')) {
            event.preventDefault();
            const idProducto = target.closest('.remove-item-btn').dataset.idProducto;

            Swal.fire({
                title: '¿Quitar producto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    callCartApi('remove', { id_producto: idProducto }).then(response => {
                        if (response && response.success) {
                            cartItem.remove(); // Elimina el elemento del DOM
                            // Aquí puedes añadir una función para recalcular el total
                            Swal.fire('Eliminado', 'El producto ha sido quitado.', 'success');
                        } else {
                            Swal.fire('Error', 'No se pudo quitar el producto.', 'error');
                        }
                    });
                }
            });
        }

        // Botón para vaciar todo el carrito.
        if (target.closest('.btn-vaciar-carrito')) {
            Swal.fire({
                title: '¿Vaciar todo el carrito?',
                text: "Esta acción eliminará todos los productos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, ¡vaciarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Llama a la API con la acción 'clear'
                    callCartApi('clear').then(response => {
                        if (response && response.success) {
                            // Recarga la página para mostrar el carrito vacío.
                            window.location.reload();
                        } else {
                            Swal.fire('Error', 'No se pudo vaciar el carrito.', 'error');
                        }
                    });
                }
            });
        }
    });
});