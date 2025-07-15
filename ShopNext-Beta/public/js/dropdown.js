document.addEventListener('DOMContentLoaded', function() {
    const userMenuContainer = document.querySelector('.user-menu-container');
    if (userMenuContainer) {
        const userIcon = userMenuContainer.querySelector('.user-icon');
        const dropdownMenu = userMenuContainer.querySelector('.dropdown-content');

        if (userIcon && dropdownMenu) {
            userIcon.addEventListener('click', function(event) {
                // Detiene la propagación para que el clic no cierre el menú inmediatamente
                event.stopPropagation(); 
                dropdownMenu.classList.toggle('show');
            });
        }
    }

    // Cierra el menú si se hace clic en cualquier otro lugar de la página
    window.addEventListener('click', function() {
        const allDropdowns = document.querySelectorAll('.dropdown-content');
        allDropdowns.forEach(menu => {
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });
    });
});