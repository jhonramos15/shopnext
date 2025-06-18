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
