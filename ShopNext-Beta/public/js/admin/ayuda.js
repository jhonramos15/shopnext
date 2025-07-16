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

// public/js/admin/ayuda.js
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('#ayuda-table tbody');

    if (!tableBody) return;

    tableBody.addEventListener('click', function(event) {
        const responderBtn = event.target.closest('.responder-btn');

        if (responderBtn) {
            event.preventDefault();
            const row = responderBtn.closest('tr');
            const ticketId = row.dataset.id;
            
            // Confirmación antes de cambiar el estado
            Swal.fire({
                title: '¿Marcar como Resuelto?',
                text: "Esta acción cambiará el estado del ticket.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, resolver',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    resolverTicket(ticketId, row);
                }
            });
        }
    });

    function resolverTicket(id, rowElement) {
        const formData = new URLSearchParams();
        formData.append('id_ticket', id);
        formData.append('accion', 'resolver'); // Enviamos la acción que queremos realizar

        fetch('../../../controllers/admin/accionesTicket.php', {
            method: 'POST',
            credentials: 'same-origin', // Importante para la sesión
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Éxito!', 'El ticket ha sido marcado como resuelto.', 'success');
                
                // Actualizamos la fila en la tabla al instante
                const estadoCell = rowElement.querySelector('.status.status-abierto');
                if (estadoCell) {
                    estadoCell.textContent = 'Resuelto';
                    estadoCell.classList.remove('status-abierto');
                    estadoCell.classList.add('status-resuelto');
                }
            } else {
                Swal.fire('Error', data.error || 'No se pudo actualizar el ticket.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error de Conexión', 'Hubo un problema al contactar con el servidor.', 'error');
        });
    }
});