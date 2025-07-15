document.addEventListener('DOMContentLoaded', () => {
    // 1. Buscamos el contenedor principal de los productos.
    const productsContainer = document.getElementById('products-container');

    // 2. Si el contenedor no existe en la página, no hacemos nada más.
    if (!productsContainer) {
        return; 
    }

    // 3. Creamos UN SOLO "escuchador" de eventos para todo el contenedor.
    productsContainer.addEventListener('submit', function(event) {
        
        // 4. Verificamos si el evento fue disparado por un formulario de carrito.
        if (event.target.matches('.add-to-cart-form')) {
            
            // 5. ¡LA CLAVE! Prevenimos que el formulario se envíe de la forma tradicional.
            event.preventDefault(); 

            const form = event.target;
            const formData = new FormData(form);
            const controllerURL = '/shopnext/ShopNext-Beta/controllers/cart/carritoController.php';

            // 6. Hacemos la petición al servidor.
            fetch(controllerURL, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Primero, obtenemos la respuesta como texto para poder depurar.
                return response.text().then(text => {
                    try {
                        // Intentamos convertir el texto a JSON.
                        return JSON.parse(text);
                    } catch (error) {
                        // Si falla, el servidor envió algo que no es JSON (probablemente un error de PHP).
                        console.error("La respuesta del servidor no es JSON:", text);
                        throw new Error('El servidor respondió con un formato inesperado.');
                    }
                });
            })
            .then(data => {
                // 7. Analizamos la respuesta JSON del servidor.
                if (data.error) {
                    if (data.error === 'login_required') {
                        // Usuario no logueado: le pedimos que inicie sesión.
                        Swal.fire({
                            icon: 'info',
                            title: '¡Un momento!',
                            text: 'Debes iniciar sesión para añadir productos al carrito.',
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        // Otro tipo de error reportado por el servidor.
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    }
                } else if (data.success) {
                    // ¡Éxito! El producto fue añadido.
                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto añadido!',
                        text: data.success,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                // 8. Capturamos cualquier error de red o de la lógica anterior.
                console.error('Error en la solicitud fetch:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudo añadir el producto. Revisa la consola para más detalles.'
                });
            });
        }
    });
});