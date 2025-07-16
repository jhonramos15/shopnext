// public/js/tienda.js
document.addEventListener('DOMContentLoaded', () => {

    document.body.addEventListener('submit', function(event) {
        // Solo actuamos si se envía un formulario para añadir al carrito.
        if (event.target.matches('.add-to-cart-form')) {
            // ¡Prevenimos que la página se recargue!
            event.preventDefault(); 
            
            const form = event.target;
            const formData = new FormData(form);

            // Enviamos los datos al controlador en segundo plano.
            fetch('/shopnext/ShopNext-Beta/controllers/carritoController.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                // Leemos la respuesta del PHP.
                if (response.error && response.error === 'login_required') {
                    // Si el PHP dice que se necesita login, mostramos la alerta de Swal.
                    Swal.fire({
                        icon: 'info',
                        title: 'Inicia Sesión',
                        text: 'Necesitas iniciar sesión como cliente para poder comprar.',
                        confirmButtonText: 'Ir a Login'
                    }).then(() => {
                        // Y después redirigimos a la URL correcta.
                        window.location.href = '/shopnext/ShopNext-Beta/views/auth/login.php';
                    });
                } else if (response.success) {
                    // Si el PHP dice que todo salió bien, mostramos la alerta de éxito.
                    Swal.fire('¡Añadido!', 'El producto se ha añadido a tu carrito.', 'success');
                } else {
                    // Para cualquier otro error.
                    Swal.fire('Error', 'No se pudo añadir el producto.', 'error');
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
            });
        }
    });
});