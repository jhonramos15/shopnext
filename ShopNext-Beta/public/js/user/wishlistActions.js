document.addEventListener('DOMContentLoaded', function () {
    const moveAllButton = document.querySelector('.move-all-btn');

    if (moveAllButton) {
        moveAllButton.addEventListener('click', function () {
            
            // Usamos SweetAlert2 para una confirmación
            Swal.fire({
                title: '¿Mover todo al carrito?',
                text: "Todos los productos de tu lista de deseos se añadirán a tu carrito de compras.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, mover todo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviamos la solicitud al servidor
                    procederAMover();
                }
            });
        });
    }

    function procederAMover() {
        fetch('/shopnext/ShopNext-Beta/controllers/user/moverFavoritosACarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Redirigir al carrito después de mostrar el mensaje
                    window.location.href = data.redirectUrl;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            Swal.fire('Error de Conexión', 'Hubo un problema de comunicación con el servidor.', 'error');
        });
    }
});