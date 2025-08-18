// En: public/js/shop/product-details.js

document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA PARA EL SELECTOR DE CANTIDAD ---
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const quantityInput = document.getElementById('quantity-input');

    decreaseBtn?.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value, 10);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    increaseBtn?.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value, 10);
        quantityInput.value = currentValue + 1;
    });

    // --- LÓGICA PARA AÑADIR A FAVORITOS ---
    const favButton = document.getElementById('add-to-favorites-btn');
    favButton?.addEventListener('click', function() {
        const idProducto = this.dataset.idProducto;
        console.log(`Acción de favoritos para el producto ${idProducto}`);
        // Aquí iría tu lógica fetch para la API de favoritos
        this.classList.toggle('is-favorite');
    });

// --- LÓGICA PARA AÑADIR AL CARRITO ---
const cartForm = document.getElementById('form-add-to-cart');

cartForm?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // ✅ La ruta ahora apunta a nuestro router principal
    fetch(`${App.baseUrl}index.php?action=cart-api`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Si la respuesta no es JSON, la mostramos como texto para depurar
        if (!response.headers.get('content-type')?.includes('application/json')) {
             return response.text().then(text => { throw new Error('Respuesta inesperada del servidor: ' + text) });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Añadido!',
                text: 'Producto añadido al carrito correctamente.',
                timer: 1500,
                showConfirmButton: false
            });
            // Aquí podrías actualizar un contador del carrito en el header
        } else {
            throw new Error(data.message || 'Error al añadir al carrito.');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message
        });
    });
});
});