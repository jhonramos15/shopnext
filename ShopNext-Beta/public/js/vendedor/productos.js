document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ productos.js cargado');

    // --- ELEMENTOS DEL MODAL ---
    const editModal = document.getElementById('edit-product-modal');
    const editForm = document.getElementById('edit-product-form');
    const cancelBtn = document.getElementById('cancel-edit-btn');
    const tableBody = document.querySelector('.table-section tbody');

    if (!tableBody) {
        console.error("No se encontró el cuerpo de la tabla.");
        return;
    }

    // --- ESCUCHADOR DE CLICS PARA TODA LA TABLA ---
    tableBody.addEventListener('click', function(event) {
        const editButton = event.target.closest('.edit-btn');
        const deleteButton = event.target.closest('.delete-btn');

        // --- LÓGICA PARA EL BOTÓN DE ELIMINAR ---
        if (deleteButton) {
            event.preventDefault();
            const row = deleteButton.closest('tr');
            const idProducto = row.dataset.id;
            
            // Usamos SweetAlert para una confirmación más elegante
            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                const data = new FormData();
                data.append('accion', 'eliminar');
                data.append('id_producto', idProducto);

                // Llamamos al controlador correcto
                fetch('../../../controllers/uploads/productActionVendedor.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('¡Éxito! ' + data.success);
                        row.remove(); // Quitamos la fila de la tabla al instante
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => console.error('Error en fetch:', error));
            }
        }

        // --- LÓGICA PARA EL BOTÓN DE EDITAR ---
        if (editButton) {
            event.preventDefault();
            const row = editButton.closest('tr');
            
            // Llenamos el formulario del modal con los datos de la fila
            document.getElementById('edit-product-id').value = row.dataset.id;
            document.getElementById('edit-nombre').value = row.cells[0].querySelector('span').textContent;
            document.getElementById('edit-categoria').value = row.cells[1].textContent;
            document.getElementById('edit-precio').value = parseFloat(row.cells[2].textContent.replace('$', '').replace(/,/g, ''));
            document.getElementById('edit-stock').value = parseInt(row.cells[3].textContent);
            
            editModal.style.display = 'flex';
        }
    });

    // --- FUNCIONALIDAD DEL MODAL ---
    // Cerrar el modal
    if(cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            editModal.style.display = 'none';
        });
    }

    // Enviar el formulario de edición
    if(editForm) {
        editForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const data = new FormData(this);
            data.append('accion', 'editar');

            fetch('../../../controllers/uploads/productActionVendedor.php', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('¡Actualizado! ' + data.success);
                    location.reload(); // Recargamos la página para ver los cambios
                } else {
                    alert('Error: ' + data.error);
                }
            });
        });
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

    