document.addEventListener('DOMContentLoaded', function () {
    const reviewForm = document.getElementById('review-form');
    if (!reviewForm) return;

    const messageContainer = document.getElementById('review-form-message');
    const submitButton = reviewForm.querySelector('button[type="submit"]');

    reviewForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(reviewForm);
        
        submitButton.disabled = true;
        messageContainer.textContent = 'Enviando...';
        messageContainer.style.color = 'black';

        // --- ¡¡ESTA ES LA LÍNEA CORREGIDA!! ---
        // Desde 'views/pages/', necesitamos subir dos niveles (../../) para llegar a la raíz
        const fetchURL = '../../controllers/guardarResena.php'; 

        fetch(fetchURL, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Si la respuesta del servidor no es exitosa (ej: 404, 500)
                return response.text().then(text => { 
                    throw new Error("El servidor devolvió una respuesta inesperada: " + text);
                });
            }
            // Si la respuesta es exitosa (2xx), la procesamos como JSON
            return response.json();
        })
        .then(data => {
            if (data.success) {
                messageContainer.textContent = data.message;
                messageContainer.style.color = 'green';
                reviewForm.reset();
                setTimeout(() => window.location.reload(), 2000);
            } else {
                // Errores controlados devueltos por nuestro PHP
                throw new Error(data.message);
            }
        })
        .catch(error => {
            // Capturamos cualquier error y lo mostramos
            messageContainer.textContent = `Error: ${error.message}`;
            messageContainer.style.color = 'red';
        })
        .finally(() => {
            // Se ejecuta siempre, al final
            submitButton.disabled = false;
        });
    });
});

function showLoginAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Inicia Sesión',
        text: 'Necesitas iniciar sesión para poder comprar.',
        confirmButtonText: 'Ir a Login',
        confirmButtonColor: '#8E06C2'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../auth/login.php';
        }
    });
}