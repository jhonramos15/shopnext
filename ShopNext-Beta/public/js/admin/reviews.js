document.addEventListener('DOMContentLoaded', () => {
    const reviewsTableBody = document.querySelector('.table-section table tbody');

    if (reviewsTableBody) {
        reviewsTableBody.addEventListener('click', function(e) {
            const approveBtn = e.target.closest('.approve-btn');
            const rejectBtn = e.target.closest('.reject-btn');

            if (approveBtn) {
                e.preventDefault();
                const row = approveBtn.closest('tr');
                const reviewId = row.dataset.id;
                updateReviewStatus(reviewId, 'aprobado', row);
            }

            if (rejectBtn) {
                e.preventDefault();
                const row = rejectBtn.closest('tr');
                const reviewId = row.dataset.id;
                updateReviewStatus(reviewId, 'rechazado', row);
            }
        });
    }
});

/**
 * Envía la solicitud para actualizar el estado de una reseña al servidor.
 * @param {number} id - El ID de la reseña.
 * @param {string} status - El nuevo estado ('aprobado' o 'rechazado').
 * @param {HTMLElement} rowElement - El elemento <tr> de la tabla para actualizar la UI.
 */
async function updateReviewStatus(id, status, rowElement) {
    try {
        const response = await fetch(`${App.baseUrl}index.php?action=admin&page=reviews&crud=update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_resena: id,
                estado: status
            }),
        });

        const result = await response.json();

if (result.success) {
    if (status === 'aprobado') {
        // ✅ Si se aprueba, solo actualizamos la fila en su sitio.
        const statusBadge = rowElement.querySelector('.status');
        statusBadge.textContent = 'Aprobado';
        statusBadge.className = 'status aprobado';
        rowElement.querySelector('.table-actions').innerHTML = '✔️ Aprobada';
    } else if (status === 'rechazado') {
        // ❌ Si se rechaza, hacemos que la fila desaparezca con una animación.
        rowElement.style.transition = 'opacity 0.5s ease';
        rowElement.style.opacity = '0';
        setTimeout(() => {
            rowElement.remove(); // Elimina la fila del HTML
        }, 500); // Esperamos medio segundo a que termine la animación
    }
}   else {
            alert('Error al actualizar la reseña: ' + (result.message || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error de red:', error);
        alert('Hubo un problema de conexión. Inténtalo de nuevo.');
    }
}