// En: public/js/shop/review-form.js

document.addEventListener('DOMContentLoaded', () => {
    const reviewForm = document.getElementById('form-resena');
    
    reviewForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        if (!formData.get('puntuacion')) {
            Swal.fire('Error', 'Por favor, selecciona una calificación de estrellas.', 'error');
            return;
        }

        fetch(`${App.baseUrl}index.php?action=guardar-resena`, { // Apunta al router
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Si la respuesta no es JSON, la mostramos como texto para depurar
            if (!response.headers.get('content-type')?.includes('application/json')) {
                 return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('¡Gracias!', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                throw new Error(data.message || 'Ocurrió un error inesperado.');
            }
        })
        .catch(error => {
            Swal.fire('Error al enviar', error.message, 'error');
        });
    });
});