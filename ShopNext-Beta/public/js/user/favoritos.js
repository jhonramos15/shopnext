// public/js/user/favoritos.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Buscamos TODOS los botones de corazón que haya en la página
    document.querySelectorAll('.add-to-favorites').forEach(button => {
        // 2. A cada botón, le añadimos un escuchador de clics
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Evitamos que el enlace recargue la página

            const productId = this.dataset.productId;
            const iconElement = this.querySelector('i'); // Obtenemos el ícono <i> para cambiar su color

            // 3. Llamamos a TU función toggleFavorito con los datos correctos
            toggleFavorito(productId, iconElement);
        });
    });
});

/**
 * Tu función para añadir/eliminar favoritos (¡está excelente!)
 * Solo hemos cambiado la URL del fetch.
 */
function toggleFavorito(productoId, iconElement) {
    const formData = new FormData();
    formData.append('id_producto', productoId);

    // ✅ ¡ESTE ES EL ÚNICO CAMBIO IMPORTANTE!
    // Ahora apuntamos al router con una acción clara y fácil de entender.
    fetch('index.php?action=toggle-favorite', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                iconElement.classList.add('active'); // Corazón se pone rojo
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Añadido a favoritos',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (data.action === 'removed') {
                iconElement.classList.remove('active'); // Corazón vuelve a la normalidad
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Eliminado de favoritos',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } else {
            // Tu lógica de errores (está perfecta)
            if (data.error === 'login_required') {
                Swal.fire('Inicia Sesión', 'Necesitas una cuenta para añadir a favoritos.', 'warning');
            } else {
                Swal.fire('Error', data.error || 'Ocurrió un error inesperado.', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error en fetch:', error);
        Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}