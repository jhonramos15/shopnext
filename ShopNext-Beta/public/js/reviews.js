document.addEventListener('DOMContentLoaded', function () {
    const reviewForm = document.getElementById('review-form');

    // Si no se encuentra el formulario en la página, no hacemos nada.
    if (!reviewForm) {
        return;
    }

    reviewForm.addEventListener('submit', function (event) {
        // Prevenimos el envío tradicional del formulario
        event.preventDefault();

        const formData = new FormData(reviewForm);

        // Hacemos la petición al backend para guardar la reseña
        fetch('../../controllers/guardarResena.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Si la respuesta no es OK (ej: error 401 o 500), lanzamos un error
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Si la reseña se guardó con éxito
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Reseña Enviada!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recargamos la página para que el usuario vea su nueva reseña
                    window.location.reload();
                });
            }
        })
        .catch(error => {
            // Si ocurre cualquier error (de red, del servidor, etc.), lo mostramos
            console.error('Error al enviar la reseña:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                // Usamos el mensaje del error si está disponible, si no, uno genérico
                text: error.message || 'No se pudo guardar la reseña. Inténtalo de nuevo.'
            });
        });
    });
});