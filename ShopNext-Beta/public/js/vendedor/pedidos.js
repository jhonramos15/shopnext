document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.pathname.split('/').pop(); // ejemplo: "clientes.html"
    const menuLinks = document.querySelectorAll('.menu li a');

    menuLinks.forEach(link => {
      const linkPage = link.getAttribute('href').split('/').pop(); // Extrae solo el nombre del archivo del href
      // Si el href es exactamente igual al nombre de la página actual, o si es la página de ingresos
      if (linkPage === currentPage || (currentPage === 'ingresos.html' && linkPage === 'ingresos.html')) { // Ajuste para ingresos.html
        link.parentElement.classList.add('active'); //
      } else {
        link.parentElement.classList.remove('active'); //
      }
    });

    lucide.createIcons(); // Asegura íconos visibles

    // JavaScript para el menú desplegable del perfil
    const userProfileBtn = document.querySelector('.user'); // Selecciona el div .user para el botón del perfil
    const profileDropdownMenu = document.getElementById('profileDropdownMenu'); // Asegúrate de añadir un id="profileDropdownMenu" al div del menú desplegable del perfil en tu HTML si lo tienes.
    const profileArrow = userProfileBtn ? userProfileBtn.querySelector('.profile-arrow') : null; // Asegúrate de tener una flecha con clase .profile-arrow dentro de .user

    if (userProfileBtn) { // Solo si el botón de perfil existe
        userProfileBtn.addEventListener('click', () => {
            if (profileDropdownMenu) { // Solo si el menú desplegable existe
                profileDropdownMenu.classList.toggle('show');
            }
            if (profileArrow) { // Solo si la flecha existe
                profileArrow.classList.toggle('rotate');
            }
        });

        // Cerrar el menú si se hace clic fuera de él
        window.addEventListener('click', function(event) {
            if (profileDropdownMenu && userProfileBtn && !userProfileBtn.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
                if (profileDropdownMenu.classList.contains('show')) {
                    profileDropdownMenu.classList.remove('show');
                    if (profileArrow) {
                        profileArrow.classList.remove('rotate');
                    }
                }
            }
        });
    }
});

// public/js/vendedor/pedidos.js
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('#pedidos-table tbody');
    if (!tableBody) return;

    // Usamos un solo escuchador para toda la tabla
    tableBody.addEventListener('click', function(event) {
        const editButton = event.target.closest('.edit-status-btn');
        if (!editButton) return;

        event.preventDefault();
        const row = editButton.closest('tr');
        const pedidoId = row.dataset.id;
        const estadoActual = row.dataset.estado;

        // Abrimos el modal de SweetAlert2 para cambiar el estado
        Swal.fire({
            title: `Cambiar estado del Pedido #${pedidoId}`,
            input: 'select',
            inputOptions: {
                'pendiente': 'Pendiente',
                'procesado': 'Procesado',
                'enviado': 'Enviado',
                'entregado': 'Entregado',
                'cancelado': 'Cancelado'
            },
            inputValue: estadoActual,
            showCancelButton: true,
            confirmButtonText: 'Actualizar Estado',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#7f56d9',
        }).then((result) => {
            if (result.isConfirmed) {
                const nuevoEstado = result.value;
                actualizarEstadoPedido(pedidoId, nuevoEstado, row);
            }
        });
    });

    function actualizarEstadoPedido(id, estado, rowElement) {
        // Usamos URLSearchParams que es ideal para 'application/x-www-form-urlencoded'
        const formData = new URLSearchParams();
        formData.append('id_pedido', id);
        formData.append('estado', estado);

        fetch('../../../controllers/vendedor/accionesPedido.php', {
            method: 'POST',
            credentials: 'same-origin', // ¡No olvidar esto para que la sesión funcione!
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Actualizado!', 'El estado del pedido ha sido cambiado.', 'success');
                
                // Actualizamos la fila en la tabla al instante, sin recargar la página
                const estadoBadge = rowElement.querySelector('.status');
                estadoBadge.textContent = estado;
                estadoBadge.className = `status ${estado.toLowerCase()}`; // Actualiza la clase para el color
                rowElement.dataset.estado = estado; // Actualiza el estado guardado en la fila
            } else {
                Swal.fire('Error', data.error || 'No se pudo actualizar el estado.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error de Conexión', 'Hubo un problema con el servidor.', 'error');
        });
    }
});