document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.pathname.split('/').pop(); // ejemplo: "clientes.html"
    const menuLinks = document.querySelectorAll('.menu li a');

    menuLinks.forEach(link => {
      const linkPage = link.getAttribute('href');

      if (linkPage === currentPage) {
        link.parentElement.classList.add('active');
      } else {
        link.parentElement.classList.remove('active');
      }
    });

    lucide.createIcons(); // Asegura íconos visibles
  });


    lucide.createIcons();

    // JavaScript para el menú desplegable del perfil
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    const profileArrow = userProfileBtn.querySelector('.profile-arrow');

    userProfileBtn.addEventListener('click', () => {
        profileDropdownMenu.classList.toggle('show');
        profileArrow.classList.toggle('rotate');
    });

    // Cerrar el menú si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (!userProfileBtn.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
            if (profileDropdownMenu.classList.contains('show')) {
                profileDropdownMenu.classList.remove('show');
                profileArrow.classList.remove('rotate');
            }
        }
    });