// public/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA EL MENÚ DESPLEGABLE DEL PERFIL ---
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    const profileArrow = userProfileBtn ? userProfileBtn.querySelector('.profile-arrow') : null;

    if (userProfileBtn && profileDropdownMenu) {
        userProfileBtn.addEventListener('click', function(event) {
            // Detenemos la propagación para que el clic en el botón no cierre el menú inmediatamente
            event.stopPropagation(); 
            
            profileDropdownMenu.classList.toggle('show');
            if (profileArrow) {
                profileArrow.classList.toggle('rotate');
            }
        });
    }

    // Cierra el menú si se hace clic en cualquier otro lugar de la página
    window.addEventListener('click', function() {
        if (profileDropdownMenu && profileDropdownMenu.classList.contains('show')) {
            profileDropdownMenu.classList.remove('show');
            if (profileArrow) {
                profileArrow.classList.remove('rotate');
            }
        }
    });

    // Añadimos esto para que los íconos de Lucide siempre se rendericen
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});