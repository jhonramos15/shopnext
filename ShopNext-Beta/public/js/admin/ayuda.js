document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#ayuda-table tbody');
    if (!tableBody) return;

    const modalOverlay = document.getElementById('edit-modal-overlay');
    const editForm = document.getElementById('edit-ticket-form');
    const cancelBtn = document.getElementById('cancel-edit-btn');

    const openEditModal = (ticketData) => {
        document.getElementById('edit-ticket-id').value = ticketData.id_ticket;
        document.getElementById('edit-asunto').value = ticketData.asunto;
        document.getElementById('edit-prioridad').value = ticketData.prioridad;
        document.getElementById('edit-estado').value = ticketData.estado;
        modalOverlay.style.display = 'flex';
    };

    tableBody.addEventListener('click', function (e) {
        const button = e.target.closest('.action-icon');
        if (!button) return;

        const row = button.closest('tr');
        const ticketId = row.dataset.id;

        if (button.classList.contains('edit-btn')) {
            e.preventDefault();
            fetch(`${App.baseUrl}?action=admin&page=help&crud=get_data&id=${ticketId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        openEditModal(result.data);
                    } else {
                        Swal.fire('Error', result.message || 'No se pudieron obtener los datos del ticket.', 'error');
                    }
                });
        }

        if (button.classList.contains('delete-btn')) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar este ticket?',
                text: "El ticket se marcará como 'Cerrado' y se archivará.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡cerrar ticket!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${App.baseUrl}?action=admin&page=help&crud=delete`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_ticket: ticketId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Cerrado!', 'El ticket ha sido cerrado.', 'success');
                            row.style.transition = 'opacity 0.5s ease';
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 500);
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo cerrar el ticket.', 'error');
                        }
                    });
                }
            });
        }
    });

    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editForm);
            const data = Object.fromEntries(formData.entries());

            fetch(`${App.baseUrl}?action=admin&page=help&crud=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({ title: '¡Actualizado!', icon: 'success', timer: 1500, showConfirmButton: false });
                    
                    const row = document.querySelector(`tr[data-id='${data.id_ticket}']`);
                    const prioritySpan = row.querySelector('.status[class*="priority-"]');
                    const statusSpan = row.querySelector('.status[class*="status-"]');

                    prioritySpan.textContent = data.prioridad;
                    prioritySpan.className = `status priority-${data.prioridad.toLowerCase()}`;
                    
                    statusSpan.textContent = data.estado;
                    statusSpan.className = `status status-${data.estado.toLowerCase().replace(' ', '-')}`;

                    modalOverlay.style.display = 'none';
                } else {
                    Swal.fire('Error', result.message || 'No se pudo actualizar el ticket.', 'error');
                }
            });
        });
    }

    if (cancelBtn) cancelBtn.addEventListener('click', () => modalOverlay.style.display = 'none');
    if (modalOverlay) modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.style.display = 'none';
        }
    });
});
