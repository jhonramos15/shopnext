/**
 * ======================================================================
 * FUNCIÓN GLOBAL PARA AÑADIR AL CARRITO
 * Se define en el ámbito global para que sea accesible desde cualquier
 * 'onclick' en tu HTML.
 * ======================================================================
 */
function agregarAlCarrito(productoId) {
    const cantidadInput = document.getElementById('quantity');
    const cantidad = cantidadInput ? cantidadInput.value : 1;

    const formData = new FormData();
    formData.append('id_producto', productoId);
    formData.append('cantidad', cantidad);

    fetch("/shopnext/ShopNext-Beta/controllers/cart/carritoController.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('¡Añadido!', 'El producto se ha añadido a tu carrito.', 'success');
        } else if (data.error && data.error === 'login_required') {
            Swal.fire('Inicia Sesión', 'Necesitas iniciar sesión para poder comprar.', 'info');
        } else {
            Swal.fire('Error', data.error || 'No se pudo añadir el producto.', 'error');
        }
    })
    .catch(error => {
        console.error('Error en fetch:', error);
        Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}

/**
 * ======================================================================
 * LÓGICA QUE SE EJECUTA CUANDO EL DOM ESTÁ LISTO
 * ======================================================================
 */
document.addEventListener('DOMContentLoaded', function () {

    // Listener para los formularios de "Añadir al carrito" (como en el index).
    document.addEventListener('submit', function(event) {
        if (event.target.matches('.add-to-cart-form')) {
            event.preventDefault();
            const form = event.target;
            const productoIdInput = form.querySelector('input[name="id_producto"]');
            if (productoIdInput) {
                agregarAlCarrito(productoIdInput.value);
            }
        }
    });

    // --- LÓGICA PARA LA VISTA DEL CARRITO (carrito.php) ---
    const cartItemsContainer = document.getElementById('cart-items');
    if (!cartItemsContainer) return;

    const updateCartTotal = () => {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.querySelector('.product-price').dataset.price);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            total += price * quantity;
        });
        const totalElement = document.getElementById('cart-total');
        if (totalElement) {
            totalElement.textContent = `$${total.toLocaleString('es-CO')}`;
        }
    };

    const callApi = (action, id, quantity = null) => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id_producto_carrito', id);
        if (quantity !== null) {
            formData.append('cantidad', quantity);
        }

        return fetch('/shopnext/ShopNext-Beta/controllers/cart/carritoAPI.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .catch(error => Swal.fire('Error', 'Hubo un problema de conexión.', 'error'));
    };

    cartItemsContainer.addEventListener('click', function (event) {
        const target = event.target;
        const cartItem = target.closest('.cart-item');
        if (!cartItem) return;

        const id = cartItem.dataset.id;
        const quantityInput = cartItem.querySelector('.quantity-input');
        const price = parseFloat(cartItem.querySelector('.product-price').dataset.price);
        const subtotalElement = cartItem.querySelector('.product-subtotal');
        let quantity = parseInt(quantityInput.value);

        if (target.matches('.increase-qty')) {
            quantity++;
            quantityInput.value = quantity;
            subtotalElement.textContent = `$${(price * quantity).toLocaleString('es-CO')}`;
            callApi('update', id, quantity).then(() => updateCartTotal());
        }

        if (target.matches('.decrease-qty')) {
            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;
                subtotalElement.textContent = `$${(price * quantity).toLocaleString('es-CO')}`;
                callApi('update', id, quantity).then(() => updateCartTotal());
            }
        }

        if (target.closest('.delete-product')) {
            Swal.fire({
                title: '¿Quitar producto?',
                text: "El producto será eliminado de tu carrito.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    callApi('delete', id).then(response => {
                        if (response && response.success) {
                            cartItem.remove();
                            updateCartTotal();
                            Swal.fire('Eliminado', 'El producto ha sido quitado del carrito.', 'success');
                        } else {
                            Swal.fire('Error', 'No se pudo quitar el producto.', 'error');
                        }
                    });
                }
            });
        }
    });
});