document.addEventListener('DOMContentLoaded', function () {
    const cartItemsContainer = document.getElementById('cart-items');

    // Función para actualizar el total general del carrito
    const updateCartTotal = () => {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.querySelector('.product-price').dataset.price);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            total += price * quantity;
        });
        const totalElement = document.getElementById('cart-total');
        if(totalElement) {
            totalElement.textContent = `$${total.toLocaleString('es-CO')}`;
        }
    };

    // Función genérica para llamar a la API
    const callApi = (action, id, quantity = null) => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id_producto_carrito', id);
        if (quantity !== null) {
            formData.append('cantidad', quantity);
        }
        
        // --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
        // La URL ahora apunta a "carritoAPI.php" con "API" en mayúsculas.
        return fetch('/shopnext/ShopNext-Beta/controllers/cart/carritoAPI.php', {
            method: 'POST',
            body: formData
        }).then(response => {
            if (!response.ok) {
                // Esto nos ayuda a ver el error 404 más claramente en la consola.
                throw new Error(`Error HTTP ${response.status}: No se encontró el recurso.`);
            }
            return response.json(); // Intentamos convertir la respuesta a JSON
        }).catch(error => {
            console.error('Error en la llamada a la API:', error);
            // Mostramos un error más amigable al usuario.
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema al actualizar tu carrito. Revisa la consola para más detalles.'
            });
        });
    };

    // Event delegation para manejar los clics en todo el contenedor
    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', function (event) {
            const target = event.target;
            const cartItem = target.closest('.cart-item');
            if (!cartItem) return;

            const id = cartItem.dataset.id;
            const quantityInput = cartItem.querySelector('.quantity-input');
            const price = parseFloat(cartItem.querySelector('.product-price').dataset.price);
            const subtotalElement = cartItem.querySelector('.product-subtotal');
            let quantity = parseInt(quantityInput.value);

            // --- Botón de AUMENTAR cantidad ---
            if (target.matches('.increase-qty')) {
                quantity++;
                quantityInput.value = quantity;
                subtotalElement.textContent = `$${(price * quantity).toLocaleString('es-CO')}`;
                callApi('update', id, quantity).then(() => updateCartTotal());
            }

            // --- Botón de DISMINUIR cantidad ---
            if (target.matches('.decrease-qty')) {
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                    subtotalElement.textContent = `$${(price * quantity).toLocaleString('es-CO')}`;
                    callApi('update', id, quantity).then(() => updateCartTotal());
                }
            }
        });
    }
});