// script.js

document.addEventListener('DOMContentLoaded', function() {
    
    const hamburgerButton = document.getElementById('hamburger-icon');
    const dropdownContent = document.getElementById('dropdown-content');

    // Muestra u oculta el menú al hacer clic en el botón
    hamburgerButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague al 'window'
        dropdownContent.classList.toggle('show');
    });

    // Cierra el menú si se hace clic en cualquier otro lugar de la página
    window.addEventListener('click', function(event) {
        if (!hamburgerButton.contains(event.target)) {
            dropdownContent.classList.remove('show');
        }
    });
    // --- CÓDIGO NUEVO PARA LA CONTRASEÑA ---
    const passwordInput = document.getElementById('contrasena');
    const togglePasswordIcon = document.querySelector('.toggle-password');

    // 1. Muestra el ojo solo cuando hay texto
    passwordInput.addEventListener('input', function() {
        // Si hay algo escrito, muestra el ícono. Si no, lo oculta.
        if (passwordInput.value.length > 0) {
            togglePasswordIcon.style.display = 'block';
        } else {
            togglePasswordIcon.style.display = 'none';
        }
    });

    // 2. Cambia la visibilidad de la contraseña al hacer clic en el ojo
    togglePasswordIcon.addEventListener('click', function() {
        // Revisa el tipo de input actual (password o text)
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Cambia el ícono del ojo
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

});