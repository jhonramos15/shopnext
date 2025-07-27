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

    document.addEventListener('DOMContentLoaded', function() {

    // --- Control del Modal ---
    // Primero, encontramos los elementos en la página
const modal = document.getElementById('vendedorModal');
const btnAbrirModal = document.getElementById('btnAbrirModalVendedor');
const spanCerrar = document.querySelector('.modal .close');

// Este 'if' se asegura de que el código solo se ejecute si los elementos existen
if (modal && btnAbrirModal && spanCerrar) {
    
    // ORDEN 1: Cuando se haga clic en el botón, muestra la ventana
    btnAbrirModal.onclick = function() {
        modal.style.display = "block";
    }

    // ORDEN 2: Cuando se haga clic en la 'X', oculta la ventana
    spanCerrar.onclick = function() {
        modal.style.display = "none";
    }

    // ORDEN 3: Si se hace clic fuera de la ventana, también se oculta
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

    // --- Validación del Formulario ---
    const form = document.getElementById('vendedorForm');
    const nombre = document.getElementById('nombreVendedor');
    const telefono = document.getElementById('telefonoVendedor');
    const direccion = document.getElementById('direccionVendedor');
    const password = document.getElementById('passwordVendedor');

    // Mensajes de error
    const errorNombre = document.getElementById('errorNombre');
    const errorTelefono = document.getElementById('errorTelefono');
    const errorDireccion = document.getElementById('errorDireccion');
    const errorPassword = document.getElementById('errorPassword');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío por defecto
        
        const esValido = validarFormulario();

        if (esValido) {
            // Si todo es correcto, enviamos el formulario.
            // Aquí puedes usar AJAX (fetch) para enviar los datos a tu PHP sin recargar la página.
            console.log('Formulario válido. Enviando datos...');
            
            const formData = new FormData(form);

            fetch('registroVendedorController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if(data.success) {
                    alert('¡Vendedor registrado con éxito!');
                    modal.style.display = 'none'; // Cierra el modal
                    form.reset(); // Limpia el formulario
                } else {
                    alert('Error en el registro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al conectar con el servidor.');
            });

        } else {
            console.log('El formulario contiene errores.');
        }
    });

    

    function validarFormulario() {
        let isValid = true;
        // Limpiar errores previos
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

        // 1. Validación de Nombre
        const nombreRegex = /^[A-Za-z\s]{5,35}$/;
        if (!nombreRegex.test(nombre.value)) {
            errorNombre.textContent = 'El nombre debe tener entre 5 y 35 caracteres y solo contener letras.';
            errorNombre.style.display = 'block';
            isValid = false;
        }

        // 2. Validación de Teléfono
        const telefonoRegex = /^\d{10}$/;
        if (!telefonoRegex.test(telefono.value)) {
            errorTelefono.textContent = 'El teléfono debe contener exactamente 10 dígitos numéricos.';
            errorTelefono.style.display = 'block';
            isValid = false;
        }

        // 3. Validación de Dirección
        if (direccion.value.trim().length < 5) { // Una validación básica para que no esté vacío
            errorDireccion.textContent = 'La dirección es obligatoria.';
            errorDireccion.style.display = 'block';
            isValid = false;
        }

        // 4. Validación de Contraseña
        // Mínimo 7, máximo 30, una letra, un número, un caracter especial
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{7,30}$/;
        if (!passwordRegex.test(password.value)) {
            errorPassword.textContent = 'La contraseña debe tener entre 7-30 caracteres, incluir al menos un número, una letra y un caracter especial (@$!%*#?&).';
            errorPassword.style.display = 'block';
            isValid = false;
        }
        
        return isValid;
    }
});

});