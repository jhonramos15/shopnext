// public/js/signUpVendedor.js

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA EL MENÚ HAMBURGUESA ---
    const hamburgerButton = document.getElementById('hamburger-icon');
    const dropdownContent = document.getElementById('dropdown-content');

    if (hamburgerButton && dropdownContent) {
        hamburgerButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Evita que el clic se propague al 'window'
            dropdownContent.classList.toggle('show');
        });
    }

    // Cierra el menú si se hace clic en cualquier otro lugar de la página
    window.addEventListener('click', function() {
        if (dropdownContent && dropdownContent.classList.contains('show')) {
            dropdownContent.classList.remove('show');
        }
    });

    // --- LÓGICA PARA MOSTRAR/OCULTAR CONTRASEÑA ---
    const passwordInput = document.getElementById('contrasena');
    const togglePasswordIcon = document.querySelector('.toggle-password');

    if (passwordInput && togglePasswordIcon) {
        // 1. Muestra el ojo solo cuando hay texto
        passwordInput.addEventListener('input', function() {
            if (passwordInput.value.length > 0) {
                togglePasswordIcon.style.display = 'block';
            } else {
                togglePasswordIcon.style.display = 'none';
            }
        });

        // 2. Cambia la visibilidad de la contraseña al hacer clic en el ojo
        togglePasswordIcon.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Cambia el ícono del ojo
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});