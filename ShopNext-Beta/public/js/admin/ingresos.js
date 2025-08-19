document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#pedidos-table tbody');
    if (!tableBody) return;

    const modalOverlay = document.getElementById('edit-modal-overlay');
    const editForm = document.getElementById('edit-order-form');
    const cancelBtn = document.getElementById('cancel-edit-btn');

    const openEditModal = (orderData) => {
        document.getElementById('edit-order-id').value = orderData.id_pedido;
        document.getElementById('edit-estado').value = orderData.estado;
        modalOverlay.style.display = 'flex';
    };

    tableBody.addEventListener('click', function (e) {
        const button = e.target.closest('.action-icon');
        if (!button) return;

        const row = button.closest('tr');
        const orderId = row.dataset.id;

        if (button.classList.contains('edit-btn')) {
            e.preventDefault();
            fetch(`${App.baseUrl}?action=admin&page=income&crud=get_data&id=${orderId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        openEditModal(result.data);
                    } else {
                        Swal.fire('Error', result.message || 'No se pudieron obtener los datos del pedido.', 'error');
                    }
                });
        }

        if (button.classList.contains('delete-btn')) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cancelar este pedido?',
                text: "Esta acción cambiará el estado a 'Cancelado'.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡cancelar!',
                cancelButtonText: 'Volver'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${App.baseUrl}?action=admin&page=income&crud=delete`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_pedido: orderId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Cancelado!', 'El pedido ha sido cancelado.', 'success');
                            const statusSpan = row.querySelector('.status');
                            statusSpan.textContent = 'Cancelado';
                            statusSpan.className = 'status cancelado';
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo cancelar el pedido.', 'error');
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

            fetch(`${App.baseUrl}?action=admin&page=income&crud=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({ title: '¡Actualizado!', icon: 'success', timer: 1500, showConfirmButton: false });
                    
                    const row = document.querySelector(`tr[data-id='${data.id_pedido}']`);
                    const statusSpan = row.querySelector('.status');
                    statusSpan.textContent = data.estado;
                    statusSpan.className = `status ${data.estado.toLowerCase()}`;
                    
                    modalOverlay.style.display = 'none';
                } else {
                    Swal.fire('Error', result.message || 'No se pudo actualizar el pedido.', 'error');
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
