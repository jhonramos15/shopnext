document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('.table-section tbody');
    if (!tableBody) return;

    // --- MANEJADOR DE CLICS PARA TODA LA TABLA ---
    tableBody.addEventListener('click', (event) => {
        const targetButton = event.target.closest('a.action-icon');
        if (!targetButton) return;

        event.preventDefault();
        const productRow = targetButton.closest('tr');
        const productId = productRow.dataset.id;

        // Si es el botón de eliminar
        if (targetButton.classList.contains('delete-btn')) {
            confirmarEliminacion(productId, productRow);
        }

        // Si es el botón de editar
        if (targetButton.classList.contains('edit-btn')) {
            abrirModalEdicion(productId, productRow);
        }
    });

    // --- FUNCIÓN PARA ELIMINAR (ya la tenías) ---
    function confirmarEliminacion(id, rowElement) {
        const productName = rowElement.cells[0].textContent;
        Swal.fire({
            title: '¿Estás seguro?',
            html: `Vas a eliminar el producto: <br><b>${productName}</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../../../controllers/admin/accionesProducto.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: new URLSearchParams({ 'id_producto': id })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Eliminado!', data.message, 'success');
                        rowElement.remove();
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                });
            }
        });
    }

    // --- NUEVA FUNCIÓN PARA EDITAR ---
    function abrirModalEdicion(id, rowElement) {
        // 1. Primero, obtenemos los datos actuales del producto
        fetch(`../../../controllers/admin/obtenerProducto.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire('Error', data.error, 'error');
                    return;
                }
                const producto = data.producto;

                // 2. Mostramos el formulario emergente con SweetAlert2
                Swal.fire({
                    title: 'Editar Producto',
                    html: `
                        <form id="edit-form" class="swal2-form">
                            <label>Nombre</label>
                            <input id="swal-nombre" class="swal2-input" value="${producto.nombre_producto}">
                            <label>Descripción</label>
                            <textarea id="swal-descripcion" class="swal2-textarea">${producto.descripcion}</textarea>
                            <label>Precio</label>
                            <input id="swal-precio" class="swal2-input" type="number" step="0.01" value="${producto.precio}">
                            <label>Stock</label>
                            <input id="swal-stock" class="swal2-input" type="number" value="${producto.stock}">
                        </form>
                    `,
                    confirmButtonText: 'Guardar Cambios',
                    focusConfirm: false,
                    didOpen: () => {
                        // Código para cuando se abre la alerta
                    },
                    preConfirm: () => {
                        // 3. Cuando se hace clic en "Guardar", recolectamos los datos y los enviamos
                        const formData = new URLSearchParams();
                        formData.append('id_producto', id);
                        formData.append('nombre_producto', document.getElementById('swal-nombre').value);
                        formData.append('descripcion', document.getElementById('swal-descripcion').value);
                        formData.append('precio', document.getElementById('swal-precio').value);
                        formData.append('stock', document.getElementById('swal-stock').value);

                        return fetch('../../../controllers/admin/actualizarProducto.php', {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(updateData => {
                            if (!updateData.success) {
                                throw new Error(updateData.error);
                            }
                            return updateData;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error: ${error}`);
                        });
                    }
                }).then((result) => {
                    // 4. Si todo salió bien, actualizamos la tabla en vivo
                    if (result.isConfirmed) {
                        const updatedProduct = result.value.producto;
                        rowElement.cells[0].textContent = updatedProduct.nombre_producto;
                        rowElement.cells[3].textContent = updatedProduct.stock;
                        rowElement.cells[4].textContent = `$${parseFloat(updatedProduct.precio).toFixed(2)}`;
                        
                        const estadoCell = rowElement.cells[5].querySelector('span');
                        if(updatedProduct.stock > 0) {
                            estadoCell.textContent = 'Publicado';
                            estadoCell.className = 'status active';
                        } else {
                            estadoCell.textContent = 'Agotado';
                            estadoCell.className = 'status inactive';
                        }

                        Swal.fire('¡Actualizado!', 'El producto ha sido modificado.', 'success');
                    }
                });
            });
    }
});