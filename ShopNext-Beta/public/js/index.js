document.addEventListener('DOMContentLoaded', () => {

    // --- Menú de Navegación (Hamburguesa) ---
    // Se comprueba si el botón de hamburguesa existe antes de añadir el listener.
    const hamburgerButton = document.getElementById('hamburger-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (hamburgerButton && mobileMenu) {
        hamburgerButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- Carrusel de Imágenes (Slider) ---
    // Se comprueba si el contenedor del carrusel existe.
    const carouselContainer = document.querySelector('[data-carousel="slide"]');
    
    if (carouselContainer) {
        const carouselItems = carouselContainer.querySelectorAll('[data-carousel-item]');
        const prevButton = carouselContainer.querySelector('[data-carousel-prev]');
        const nextButton = carouselContainer.querySelector('[data-carousel-next]');
        const indicators = carouselContainer.querySelectorAll('[data-carousel-slide-to]');
        let currentIndex = 0;

        const updateCarousel = () => {
            carouselItems.forEach((item, index) => {
                item.classList.toggle('hidden', index !== currentIndex);
            });
            indicators.forEach((indicator, index) => {
                indicator.setAttribute('aria-current', index === currentIndex);
            });
        };

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + carouselItems.length) % carouselItems.length;
                updateCarousel();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % carouselItems.length;
                updateCarousel();
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
        });

        // Inicia el carrusel
        updateCarousel();
    }
    
    // --- Lógica del Acordeón para FAQs o similares ---
    // Se comprueba si existen elementos de acordeón.
    const accordionItems = document.querySelectorAll('[data-accordion-item]');

    if (accordionItems.length > 0) {
        accordionItems.forEach(item => {
            const header = item.querySelector('[data-accordion-target]');
            const content = document.querySelector(header.getAttribute('data-accordion-target'));
            const icon = header.querySelector('svg');

            if (header && content) {
                header.addEventListener('click', () => {
                    const isExpanded = header.getAttribute('aria-expanded') === 'true';
                    
                    header.setAttribute('aria-expanded', !isExpanded);
                    content.classList.toggle('hidden');
                    
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                });
            }
        });
    }

    // **ESTA ES LA LÍNEA QUE PROBABLEMENTE CAUSABA EL ERROR (LÍNEA 49)**
    // Se comprueba si el dropdown del perfil de usuario existe.
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Opcional: cerrar el menú si se hace clic fuera de él
        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }

});