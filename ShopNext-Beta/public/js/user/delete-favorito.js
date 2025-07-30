document.addEventListener('DOMContentLoaded', function () {
    const wishlistGrid = document.querySelector('.wishlist-grid');

    // Si no estamos en la página de favoritos, no hacemos nada.
    if (!wishlistGrid) return;

    // Usamos delegación de eventos para un solo "escuchador"
    wishlistGrid.addEventListener('click', function (event) {
        // Verificamos si el clic fue en un botón de eliminar
        const deleteButton = event.target.closest('.delete-btn');
        if (!deleteButton) return;

        event.preventDefault(); // Prevenimos cualquier acción por defecto

        const productCard = deleteButton.closest('.product-card');
        const productId = productCard.dataset.productId;

        // Usamos SweetAlert2 para una confirmación más elegante
        Swal.fire({
            title: '¿Quitar de favoritos?',
            text: "Este producto se eliminará de tu lista.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DB4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, procedemos a eliminar
                eliminarProducto(productId, productCard);
            }
        });
    });

    function eliminarProducto(id, cardElement) {
        fetch('/shopnext/ShopNext-Beta/controllers/user/eliminarFavorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_producto: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animación de salida y eliminación del DOM
                cardElement.style.transition = 'opacity 0.4s, transform 0.4s';
                cardElement.style.opacity = '0';
                cardElement.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    cardElement.remove();
                    actualizarContador(); // Actualizamos el contador en el título
                }, 400);

            } else {
                Swal.fire('Error', data.error || 'No se pudo eliminar el producto.', 'error');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
        });
    }

    function actualizarContador() {
        const wishlistTitle = document.querySelector('.wishlist-header h1');
        const currentCount = document.querySelectorAll('.product-card').length;
        wishlistTitle.textContent = `Mi Lista de Deseos (${currentCount})`;

        // Si ya no quedan productos, muestra el mensaje de lista vacía.
        if (currentCount === 0) {
            const wishlistContainer = document.querySelector('.wishlist-grid');
            const emptyMessage = `<div class="empty-wishlist"><p>Tu lista de deseos está vacía. ¡Explora nuestros productos y añade tus favoritos!</p></div>`;
            if (wishlistContainer) {
                wishlistContainer.innerHTML = emptyMessage;
            }
        }
    }
});