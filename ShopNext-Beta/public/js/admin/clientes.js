document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#clientes-table tbody');
    if (!tableBody) return;

    const modalOverlay = document.getElementById('edit-modal-overlay');
    const editForm = document.getElementById('edit-client-form');
    const cancelBtn = document.getElementById('cancel-edit-btn');

    const openEditModal = (clientData) => {
        document.getElementById('edit-client-id').value = clientData.id_usuario;
        document.getElementById('edit-nombre').value = clientData.nombre;
        document.getElementById('edit-email').value = clientData.correo_usuario;
        document.getElementById('edit-estado').value = clientData.estado;
        modalOverlay.style.display = 'flex';
    };

    tableBody.addEventListener('click', function (e) {
        const button = e.target.closest('.action-icon');
        if (!button) return;

        const row = button.closest('tr');
        const clientId = row.dataset.id;

        if (button.classList.contains('edit-btn')) {
            e.preventDefault();
            fetch(`${App.baseUrl}?action=admin&page=clients&crud=get_data&id=${clientId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        openEditModal(result.data);
                    } else {
                        Swal.fire('Error', result.message || 'No se pudieron obtener los datos.', 'error');
                    }
                });
        }

        if (button.classList.contains('delete-btn')) {
            e.preventDefault();
            Swal.fire({
                title: '¿Archivar cliente?',
                text: "El cliente se marcará como inactivo y no se mostrará en esta lista.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡archivar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${App.baseUrl}?action=admin&page=clients&crud=delete`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_usuario: clientId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Archivado!', 'El cliente ha sido archivado.', 'success');
                            
                            // ✅ AQUÍ ESTÁ LA CORRECCIÓN:
                            // Añadimos una transición suave y luego eliminamos la fila del DOM.
                            row.style.transition = 'opacity 0.5s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                            }, 500); // Esperamos 500ms para que la animación termine

                        } else {
                            Swal.fire('Error', data.message || 'No se pudo archivar el cliente.', 'error');
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

            fetch(`${App.baseUrl}?action=admin&page=clients&crud=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({ title: '¡Actualizado!', icon: 'success', timer: 1500, showConfirmButton: false });
                    
                    const row = document.querySelector(`tr[data-id='${data.id_usuario}']`);
                    row.cells[0].textContent = data.nombre;
                    row.cells[1].textContent = data.correo_usuario;
                    const statusSpan = row.cells[3].querySelector('span');
                    statusSpan.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
                    statusSpan.className = `status ${data.estado === 'activo' ? 'active' : 'inactive'}`;
                    
                    modalOverlay.style.display = 'none';
                } else {
                    Swal.fire('Error', result.message || 'No se pudo actualizar el cliente.', 'error');
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

    // --- Lógica para el menú desplegable del perfil ---
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    const userProfileContainer = document.querySelector('.user-profile-container');

    if (userProfileBtn) {
        userProfileBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            profileDropdownMenu.classList.toggle('show');
            userProfileContainer.classList.toggle('open');
        });
    }

    window.addEventListener('click', function(event) {
        if (profileDropdownMenu && profileDropdownMenu.classList.contains('show')) {
            profileDropdownMenu.classList.remove('show');
            userProfileContainer.classList.remove('open');
        }
    });
});
