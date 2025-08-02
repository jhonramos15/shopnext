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
        // Puedes añadir un límite de stock si lo pasas desde PHP
        quantityInput.value = currentValue + 1;
    });


    // --- LÓGICA PARA AÑADIR AL CARRITO ---
    const cartForm = document.getElementById('form-add-to-cart');
    cartForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`${App.baseUrl}ruta/a/tu/carritoController.php`, { // <-- ¡IMPORTANTE! Ajusta esta ruta
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Añadido!',
                    text: 'Producto añadido al carrito correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Opcional: Actualizar el contador del carrito en el header
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

    
    // --- LÓGICA PARA EL FORMULARIO DE RESEÑAS ---
    const reviewForm = document.getElementById('form-resena');
    reviewForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Validar que se haya seleccionado una puntuación
        if (!formData.get('puntuacion')) {
            Swal.fire('Error', 'Por favor, selecciona una calificación de estrellas.', 'error');
            return;
        }

        fetch(`${App.baseUrl}guardar-resena`, { // Usamos la ruta que definimos en index.php
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Gracias!',
                    text: 'Tu reseña ha sido publicada.',
                }).then(() => {
                    // Recargar la página para ver la nueva reseña
                    location.reload();
                });
            } else {
                throw new Error(data.message || 'Ocurrió un error inesperado.');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar',
                text: error.message
            });
        });
    });


    // --- LÓGICA PARA AÑADIR A FAVORITOS (Ejemplo básico) ---
    const favButton = document.getElementById('add-to-favorites-btn');
    favButton?.addEventListener('click', function() {
        const idProducto = this.dataset.idProducto;
        // Aquí iría tu lógica fetch para añadir/quitar de favoritos
        console.log(`Intentando añadir/quitar de favoritos el producto ${idProducto}`);
        
        // Simulación visual
        this.classList.toggle('is-favorite');
        const heartIcon = this.querySelector('i');
        heartIcon.classList.toggle('fa-regular'); // Corazón vacío
        heartIcon.classList.toggle('fa-solid');   // Corazón lleno
    });

});