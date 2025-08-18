document.addEventListener('DOMContentLoaded', function () {
    const productosTableBody = document.querySelector('#productos-table tbody');
    if (!productosTableBody) return;

    const editModalOverlay = document.getElementById('edit-modal-overlay');
    const editForm = document.getElementById('edit-product-form');
    const cancelEditBtn = document.getElementById('cancel-edit-btn');

    const openEditModal = (productData) => {
        document.getElementById('edit-product-id').value = productData.id_producto;
        document.getElementById('edit-nombre').value = productData.nombre_producto;
        
        // ✅ MODIFICADO: Seleccionamos el valor en el <select> usando el id_categoria
        document.getElementById('edit-categoria').value = productData.id_categoria;

        document.getElementById('edit-stock').value = productData.stock;
        document.getElementById('edit-precio').value = parseFloat(productData.precio).toFixed(2);
        editModalOverlay.style.display = 'flex';
    };

    // El resto del código no necesita cambios
    productosTableBody.addEventListener('click', function (e) {
        const button = e.target.closest('.action-icon');
        if (!button) return;

        const row = button.closest('tr');
        const productId = row.dataset.id;

        if (button.classList.contains('edit-btn')) {
            e.preventDefault();
            fetch(`${App.baseUrl}?action=admin&page=products&crud=get_data&id=${productId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        openEditModal(result.data);
                    } else {
                        Swal.fire('Error', result.message || 'No se pudieron obtener los datos.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error de Red', 'No se pudo conectar con el servidor.', 'error'));
        }

        if (button.classList.contains('delete-btn')) {
            e.preventDefault();
            Swal.fire({
                title: '¿Archivar producto?',
                text: "El producto se ocultará de la tienda pero no se eliminará permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡archivar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${App.baseUrl}?action=admin&page=products&crud=delete`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_producto: productId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Archivado!', 'El producto ha sido archivado.', 'success');
                            row.style.transition = 'opacity 0.5s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                            }, 500);
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo archivar el producto.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error de Red', 'No se pudo conectar con el servidor.', 'error'));
                }
            });
        }
    });

    if(editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editForm);
            const data = Object.fromEntries(formData.entries());

            fetch(`${App.baseUrl}?action=admin&page=products&crud=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({ title: '¡Actualizado!', icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire('Error', result.message || 'No se pudo actualizar el producto.', 'error');
                }
            })
            .catch(() => Swal.fire('Error de Red', 'No se pudo conectar con el servidor.', 'error'));
        });
    }
    
    if(cancelEditBtn) cancelEditBtn.addEventListener('click', () => editModalOverlay.style.display = 'none');
    if(editModalOverlay) editModalOverlay.addEventListener('click', (e) => {
        if (e.target === editModalOverlay) {
            editModalOverlay.style.display = 'none';
        }
    });
});
