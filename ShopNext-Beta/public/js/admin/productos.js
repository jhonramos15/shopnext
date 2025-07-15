document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('.table-section tbody');

    // Usamos event delegation para manejar los clics en los botones de eliminar
    tableBody.addEventListener('click', (event) => {
        const deleteButton = event.target.closest('.delete-btn');

        if (deleteButton) {
            event.preventDefault(); // Prevenimos cualquier acción por defecto del enlace

            const productRow = deleteButton.closest('tr');
            const productId = productRow.dataset.id;
            const productName = productRow.cells[0].textContent;

            // Alerta de confirmación con SweetAlert2
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
                    // Si el usuario confirma, llamamos a la función para eliminar
                    eliminarProducto(productId, productRow);
                }
            });
        }
    });

    function eliminarProducto(id, rowElement) {
        const url = '../../../controllers/admin/accionesProducto.php';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_producto: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si la eliminación fue exitosa, mostramos una alerta de éxito
                Swal.fire(
                    '¡Eliminado!',
                    data.message || 'El producto ha sido eliminado.',
                    'success'
                );

                // Eliminamos la fila de la tabla con una animación suave
                rowElement.style.transition = 'opacity 0.5s ease';
                rowElement.style.opacity = '0';
                setTimeout(() => {
                    rowElement.remove();
                }, 500);

            } else {
                // Si hubo un error, mostramos una alerta de error
                Swal.fire(
                    'Error',
                    data.error || 'No se pudo eliminar el producto.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Error en la petición fetch:', error);
            Swal.fire(
                'Error de Conexión',
                'No se pudo comunicar con el servidor.',
                'error'
            );
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