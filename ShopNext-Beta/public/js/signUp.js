document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA EL MENÚ HAMBURGUESA
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

    // --- LÓGICA PARA MOSTRAR/OCULTAR CONTRASEÑA
    const passwordInput = document.getElementById('contrasena');
    const togglePasswordIcon = document.querySelector('.toggle-password');

    if (passwordInput && togglePasswordIcon) {
        // 1. Muestra el ojo solo cuando hay texto
        function toggleIconVisibility() {
             if (passwordInput.value.length > 0 || document.activeElement === passwordInput) {
                togglePasswordIcon.style.display = 'block';
            } else {
                togglePasswordIcon.style.display = 'none';
            }
        }
        
        passwordInput.addEventListener('input', toggleIconVisibility);
        passwordInput.addEventListener('focus', toggleIconVisibility);
        passwordInput.addEventListener('blur', toggleIconVisibility);
        
        // Ocultar por defecto si no hay texto
        toggleIconVisibility();


        // 2. Cambia la visibilidad de la contraseña al hacer clic en el ojo
        togglePasswordIcon.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Cambia el ícono del ojo
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // LÓGICA DE VALIDACIÓN DEL FORMULARIO
    const signupForm = document.querySelector('form[action*="registroController.php"]');

    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Detenemos el envío para validar

            // Obtener valores de los campos
            const nombre = document.getElementById('nombre').value;
            const telefono = document.getElementById('telefono').value;
            const direccion = document.getElementById('direccion').value;
            const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
            const contrasena = document.getElementById('contrasena').value;

            // Expresiones Regulares
            const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,35}$/; // Debe tener mínimo entre 5 y 35 caracteres, soporta únicamente caracteres con acento, mayusculas y minúsculas.
            const telefonoRegex = /^\d{10}$/; // Debe tener únicamente 10 dígitos, solo pueden ser números
            const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{7,30}$/; // Debe tener mínimo 7 caracteres, una mayúscula, una mínuscula, un número y un caracter especial

            // Validaciones con SweetAlert2
            if (!nombreRegex.test(nombre.trim())) {
                Swal.fire({ icon: 'error', title: 'Error en Nombre', text: 'Debe contener solo letras y máximo 35 caracteres.' });
                return;
            }

            if (!telefonoRegex.test(telefono)) {
                Swal.fire({ icon: 'error', title: 'Error en Teléfono', text: 'Debe contener solo números y/o 10 dígitos.' });
                return;
            }

            if (direccion.trim().length === 0 || direccion.length > 40) {
                 Swal.fire({ icon: 'error', title: 'Error en Dirección', text: 'La dirección es obligatoria y no puede exceder los 40 caracteres.' });
                return;
            }

            if (!fechaNacimiento) {
                Swal.fire({ icon: 'error', title: 'Campo Incompleto', text: 'Por favor, selecciona tu fecha de nacimiento.' });
                return;
            }
            
            const hoy = new Date();
            const fechaNac = new Date(fechaNacimiento);
            let edad = hoy.getFullYear() - fechaNac.getFullYear();
            const m = hoy.getMonth() - fechaNac.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
            }
            if (edad < 15) {
                Swal.fire({ icon: 'error', title: 'Edad no permitida', text: 'Debes ser mayor de 15 años para registrarte.' });
                return;
            }

            if (edad > 120) {
                Swal.fire({ icon: 'error', title: '¿Eres así de viejo?', text: 'La edad es mayor de 120 años.'})
                return;
            }

            if (contrasena.length < 7 || contrasena.length > 30) {
                 Swal.fire({ icon: 'error', title: 'Error en Contraseña', text: 'La contraseña debe tener entre 7 y 30 caracteres.' });
                return;
            }
            if (!passRegex.test(contrasena)) {
                Swal.fire({ icon: 'error', title: 'Contraseña Débil', text: 'Debe incluir al menos una letra, un número y un carácter especial (@$!%*?&).' });
                return;
            }

            // Si todo está correcto, envía el formulario
            Swal.fire({
                icon: 'success',
                title: '¡Validación Correcta!',
                text: 'Enviando tus datos...',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                signupForm.submit();
            });
        });
    }
});