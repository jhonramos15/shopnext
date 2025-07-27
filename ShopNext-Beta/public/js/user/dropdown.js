// Espera a que todo el contenido del DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {

    const userIcon = document.querySelector('.user-icon');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Si el ícono de usuario existe, le asignamos el evento de clic
    if (userIcon) {
        userIcon.addEventListener('click', function(event) {
            // Evita que el clic se propague al 'window' y cierre el menú inmediatamente
            event.stopPropagation(); 
            dropdownMenu.classList.toggle('show');
        });
    }

    // Cierra el menú si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        // Si el menú está abierto y no se hizo clic dentro de él
        if (dropdownMenu.classList.contains('show') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});