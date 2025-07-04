document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ JS Final v3 cargado. Este es el bueno.');

    // --- RENDERIZAR ÍCONOS PRIMERO ---
    // Se asegura de que el HTML esté en su estado final antes de hacer nada más.
    lucide.createIcons();

    // --- LÓGICA GENERAL DE LA PÁGINA (MENÚ, PERFIL) ---
    // Marcar el enlace activo en el menú
    const currentPage = window.location.pathname.split('/').pop();
    const menuLinks = document.querySelectorAll('.menu li a');
    menuLinks.forEach(link => {
        const linkPage = link.getAttribute('href').split('/').pop();
        if (linkPage === currentPage) {
            link.parentElement.classList.add('active');
        }
    });

    // Dropdown de perfil
    const userProfileBtn = document.querySelector('.user');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    if (userProfileBtn) {
        userProfileBtn.addEventListener('click', (e) => {
            // Detenemos la propagación para que el clic no cierre el menú inmediatamente
            e.stopPropagation(); 
            profileDropdownMenu?.classList.toggle('show');
        });
    }
    // Cierra el menú si se hace clic en cualquier otro lugar
    window.addEventListener('click', () => {
        profileDropdownMenu?.classList.remove('show');
    });

    // --- ESCUCHADOR DE CLICS GLOBAL PARA LAS ACCIONES DE LA TABLA ---
    document.addEventListener('click', function (event) {
        const editButton = event.target.closest('.edit-btn');
        const deleteButton = event.target.closest('.delete-btn');

        if (editButton) {
            event.preventDefault(); // Detiene el '#'
            handleEdit(editButton);
        }

        if (deleteButton) {
            event.preventDefault(); // Detiene el '#'
            handleDelete(deleteButton);
        }
    });

    // --- FUNCIONES PARA MANEJAR LAS ACCIONES ---
    function handleEdit(button) {
        const row = button.closest('tr');
        if (!row) return;

        const cells = row.cells;
        document.getElementById('edit-id').value = cells[1].textContent;
        document.getElementById('edit-nombre').value = cells[2].textContent;
        document.getElementById('edit-email').value = cells[3].textContent;
        
        document.getElementById('edit-modal-overlay').style.display = 'flex';
    }

    function handleDelete(button) {
        const row = button.closest('tr');
        if (!row || !confirm('¿Estás seguro de que quieres eliminar a este cliente?')) {
            return;
        }
        
        const idCliente = row.dataset.id;
        const formData = new FormData();
        formData.append('accion', 'eliminar');
        formData.append('id', idCliente);

        fetch('../../../controllers/clienteController.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === 'eliminado') {
                    row.remove();
                } else {
                    alert('Error del servidor: ' + result);
                }
            })
            .catch(error => console.error('Error de red al eliminar:', error));
    }

    // --- MANEJO DEL FORMULARIO DEL MODAL ---
    const editForm = document.getElementById('edit-form');
    if(editForm) {
        editForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const id = document.getElementById('edit-id').value;
            const data = new FormData(editForm);
            data.append('accion', 'editar');
            data.append('id', id);

            fetch('../../../controllers/clienteController.php', { method: 'POST', body: data })
                .then(res => res.text())
                .then(response => {
                    if (response.trim() === 'ok' || response.trim() === 'sin cambios') {
                        const tableBody = document.querySelector('.table-section tbody');
                        const rowToUpdate = tableBody.querySelector(`tr[data-id='${id}']`);
                        if (rowToUpdate) {
                            rowToUpdate.children[2].innerText = data.get('nombre');
                            rowToUpdate.children[3].innerText = data.get('email');
                        }
                        document.getElementById('edit-modal-overlay').style.display = 'none';
                    } else {
                        alert('Error al guardar: ' + response);
                    }
                })
                .catch(error => console.error('Error de red al guardar:', error));
        });
    }

    const cancelEditBtn = document.getElementById('cancel-edit');
    if(cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            document.getElementById('edit-modal-overlay').style.display = 'none';
        });
    }
});