// public/js/cart/carrito.js

document.addEventListener('submit', function(event) {
    if (event.target.matches('.add-to-cart-form')) {
        event.preventDefault(); 
        
        const form = event.target;
        const formData = new FormData(form);

        // --- INICIO DE LA CORRECCIÓN ---
        // Apuntamos directamente al archivo PHP con la ruta absoluta correcta.
        fetch('../../controllers/cart/carritoController.php', {
        // --- FIN DE LA CORRECCIÓN ---
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`Error HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(response => {
            if (response.error && response.error === 'login_required') {
                Swal.fire({
                    icon: 'info',
                    title: 'Inicia Sesión',
                    text: 'Necesitas iniciar sesión para poder comprar.',
                    confirmButtonText: 'Ir a Login'
                }).then(() => {
                    // Asegúrate que esta ruta a login.php sea la correcta
                    window.location.href = '/ShopNext-Beta/views/auth/login.php';
                });
            } else if (response.success) {
                Swal.fire('¡Añadido!', 'El producto se ha añadido a tu carrito.', 'success');
            } else {
                Swal.fire('Error', 'No se pudo añadir el producto. ' + (response.error || ''), 'error');
            }
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
        });
    }
});