// public/js/user/favoritos.js

function toggleFavorito(productoId, iconElement) {
    const formData = new FormData();
    formData.append('id_producto', productoId);

    fetch('/shopnext/ShopNext-Beta/controllers/user/favoritosController.php', {
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
                iconElement.classList.remove('active'); // Corazón vuelve a su estado normal
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
            // Si el usuario no ha iniciado sesión u ocurre otro error
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