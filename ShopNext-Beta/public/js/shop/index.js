document.addEventListener('DOMContentLoaded', () => {
    // --- Inicialización del Carrusel con Swiper.js ---
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // --- Menú de Navegación (Hamburguesa) ---
    const hamburgerButton = document.querySelector('.hamburger');
    const navMenu = document.getElementById('navMenu');
    if (hamburgerButton && navMenu) {
        hamburgerButton.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    // --- Dropdown del Perfil de Usuario ---
    const userMenuButton = document.querySelector('.user-icon');
    const userDropdown = document.getElementById('dropdownMenu');
    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Cierra el menú si se hace clic fuera de él
        window.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                if (userDropdown.classList.contains('show')) {
                    userDropdown.classList.remove('show');
                }
            }
        });
    }
});