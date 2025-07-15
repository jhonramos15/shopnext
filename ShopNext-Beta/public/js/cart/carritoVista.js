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
        document.getElementById('cart-total').textContent = `$${total.toLocaleString('es-CO')}`;
    };

    // Función genérica para llamar a la API
    const callApi = (action, id, quantity = null) => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id_producto_carrito', id);
        if (quantity !== null) {
            formData.append('cantidad', quantity);
        }

        return fetch('/shopnext/ShopNext-Beta/controllers/cart/carrito_api.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json());
    };

    // Event delegation para manejar los clics en todo el contenedor
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
            callApi('update', id, quantity).then(updateCartTotal);
        }

        // --- Botón de DISMINUIR cantidad ---
        if (target.matches('.decrease-qty')) {
            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;
                subtotalElement.textContent = `$${(price * quantity).toLocaleString('es-CO')}`;
                callApi('update', id, quantity).then(updateCartTotal);
            }
        }

        // --- Botón de ELIMINAR producto ---
        if (target.closest('.delete-product')) {
            Swal.fire({
                title: '¿Quitar producto?',
                text: "El producto se eliminará de tu carrito.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    callApi('delete', id).then(response => {
                        if (response.success) {
                            cartItem.style.transition = 'opacity 0.5s ease';
                            cartItem.style.opacity = '0';
                            setTimeout(() => {
                                cartItem.remove();
                                updateCartTotal();
                                // Si el carrito queda vacío
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    cartItemsContainer.innerHTML = '<p class="empty-cart">Tu carrito está vacío.</p>';
                                }
                            }, 500);
                        }
                    });
                }
            });
        }
    });
});