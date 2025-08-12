document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA EL MENÚ DESPLEGABLE DEL PERFIL (VERSIÓN SEGURA) ---
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    // ✅ Buscamos el contenedor que daba el error
    const userProfileContainer = document.querySelector('.user-profile-container');

    // Verificamos que TODOS los elementos necesarios existan
    if (userProfileBtn && profileDropdownMenu && userProfileContainer) {
        
        userProfileBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            profileDropdownMenu.classList.toggle('show');
            // Esta línea ahora está protegida por el 'if' y no dará error
            userProfileContainer.classList.toggle('open'); 
        });

        // Cierra el menú si se hace clic fuera de él
        window.addEventListener('click', function(event) {
            if (profileDropdownMenu.classList.contains('show')) {
                if (!userProfileContainer.contains(event.target)) {
                    profileDropdownMenu.classList.remove('show');
                    userProfileContainer.classList.remove('open');
                }
            }
        });
    }

    // --- LÓGICA PARA MARCAR EL MENÚ "ACTIVO" ---
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page') || 'dashboard'; // Por defecto es 'dashboard'

    const menuLinks = document.querySelectorAll('.menu li a');
    menuLinks.forEach(link => {
        const linkUrl = new URL(link.href);
        const linkPage = linkUrl.searchParams.get('page');

        if (linkPage === currentPage) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }
    });

    // --- LÓGICA DE LAS GRÁFICAS (Tu código, sin cambios) ---
    // Esta sección solo se ejecutará en la página que tenga los <canvas>
    
    // Gráfico de Resumen de Ingresos
    const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revenueCtx) {
        // ... (Todo tu código para el gráfico de ingresos va aquí)
    }

    // Gráfico de Pedidos por Día
    const dailyOrdersCtx = document.getElementById('dailyOrdersChart')?.getContext('2d');
    if (dailyOrdersCtx) {
        // ... (Todo tu código para el gráfico de pedidos va aquí)
    }

    // Gráfico de Ventas por Categoría
    const categorySalesCtx = document.getElementById('categorySalesChart')?.getContext('2d');
    if (categorySalesCtx) {
        // ... (Todo tu código para el gráfico de categorías va aquí)
    }

    // Inicia los íconos de Lucide al final
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});