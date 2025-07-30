document.addEventListener('DOMContentLoaded', function() {

    // --- LÓGICA GENERAL DE LA PÁGINA (MENÚ, CONTRASEÑA, MODAL) ---

    // Menú Hamburguesa
    const hamburgerButton = document.getElementById('hamburger-icon');
    const dropdownContent = document.getElementById('dropdown-content');
    if (hamburgerButton && dropdownContent) {
        hamburgerButton.addEventListener('click', (event) => {
            event.stopPropagation();
            dropdownContent.classList.toggle('show');
        });
        window.addEventListener('click', () => {
            if (dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
            }
        });
    }

    // Mostrar/Ocultar Contraseña
    const passwordInput = document.getElementById('contrasena');
    const togglePasswordIcon = document.querySelector('.toggle-password');
    if (passwordInput && togglePasswordIcon) {
        togglePasswordIcon.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
    
    // Control del Modal de Vendedor
    const vendedorModal = document.getElementById('vendedorModal');
    const btnAbrirModal = document.getElementById('btnAbrirModalVendedor');
    const spanClose = document.querySelector('.modal .close');
    if (vendedorModal && btnAbrirModal && spanClose) {
        btnAbrirModal.onclick = () => vendedorModal.style.display = "block";
        spanClose.onclick = () => vendedorModal.style.display = "none";
        window.onclick = (event) => {
            if (event.target == vendedorModal) {
                vendedorModal.style.display = "none";
            }
        };
    }
    // --- MANEJO DEL FORMULARIO DE REGISTRO DE CLIENTE ---
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            // ¡CLAVE! Prevenimos que la página se recargue.
            event.preventDefault(); 

            // --- PASO 1: VALIDACIÓN DEL LADO DEL CLIENTE (Tus validaciones) ---
            const nombre = document.getElementById('nombre').value;
            const telefono = document.getElementById('telefono').value;
            const direccion = document.getElementById('direccion').value;
            const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
            const contrasena = document.getElementById('contrasena').value;
            const genero = document.getElementById('genero').value; 
            const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,35}$/;
            const telefonoRegex = /^\d{10}$/;
            const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{7,30}$/;

            if (!nombreRegex.test(nombre.trim())) {
                Swal.fire({ icon: 'error', title: 'Error en Nombre', text: 'Debe contener solo letras y tener entre 5 y 35 caracteres.' });
                return; // Detiene el proceso si la validación falla
            }
            if (!telefonoRegex.test(telefono)) {
                Swal.fire({ icon: 'error', title: 'Error en Teléfono', text: 'Debe contener exactamente 10 dígitos numéricos.' });
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
                Swal.fire({ icon: 'warning', title: '¿Estás seguro?', text: 'Tu edad parece ser mayor a 120 años.' });
                return;
            }
            if (!passRegex.test(contrasena)) {
                Swal.fire({ icon: 'error', title: 'Contraseña Débil', text: 'Debe tener entre 7 y 30 caracteres, e incluir letras, al menos un número y un carácter especial (@$!%*?&#).' });
                return;
            }
            if (!genero) {
    Swal.fire({ 
        icon: 'error', 
        title: 'Campo Incompleto', 
        text: 'Por favor, selecciona tu género.' 
    });
    return;
}   

const actionUrl = 'index.php?action=register';

// Convertimos los datos del formulario a un objeto para enviarlo como JSON
        // 2. Usamos FormData, que es más simple y efectivo para formularios
        const formData = new FormData(this);
        
        // 3. Hacemos la petición fetch
        fetch(actionUrl, {
            method: 'POST',
            body: formData // No necesitas JSON.stringify, FormData se encarga de todo.
        })
        .then(response => {
            // Primero verificamos si la respuesta es realmente JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            // Si no es JSON, es un error de PHP. Lanzamos un error para verlo.
            throw new Error('La respuesta del servidor no es JSON.');
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro Exitoso!',
                    text: data.message
                }).then(() => {
                    // Redirigimos al login
                    window.location.href = 'index.php?action=login';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el Registro',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo comunicar con el servidor. Revisa la consola para más detalles.'
            });
        });
    });
}

// --- MANEJO DEL FORMULARIO DE REGISTRO DE VENDEDOR ---
const vendedorForm = document.getElementById('vendedorForm');
if (vendedorForm) {
    vendedorForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre_vendedor').value;
        const telefono = document.getElementById('telefono_vendedor').value;
        const direccion = document.getElementById('direccion_vendedor').value;
        const contrasena = document.getElementById('contrasena_vendedor').value;

        // Reutilizamos las mismas expresiones regulares
        const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,35}$/;
        const telefonoRegex = /^\d{10}$/;
        const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{7,30}$/;

        if (!nombreRegex.test(nombre.trim())) {
            Swal.fire({ icon: 'error', title: 'Error en Nombre', text: 'Debe contener solo letras y tener entre 5 y 35 caracteres.' });
            return; // Detiene el envío
        }
        if (!telefonoRegex.test(telefono)) {
            Swal.fire({ icon: 'error', title: 'Error en Teléfono', text: 'Debe contener exactamente 10 dígitos numéricos.' });
            return;
        }
        if (direccion.trim().length === 0 || direccion.length > 40) {
            Swal.fire({ icon: 'error', title: 'Error en Dirección', text: 'La dirección es obligatoria y no puede exceder los 40 caracteres.' });
            return;
        }
        if (!passRegex.test(contrasena)) {
            Swal.fire({ icon: 'error', title: 'Contraseña Débil', text: 'Debe tener entre 7 y 30 caracteres, e incluir letras, al menos un número y un carácter especial.' });
            return;
        }

        // --- PASO 2: ENVÍO DE DATOS (FETCH) ---
        // Esta parte se queda como la teníamos, solo se ejecuta si todas las validaciones pasan
        fetch('index.php?action=register', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('vendedorModal').style.display = "none";
                Swal.fire({
                    icon: 'success',
                    title: '¡Vendedor Registrado!',
                    text: data.message
                }).then(() => {
                    window.location.href = 'index.php?action=login';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el Registro',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error en la petición de vendedor:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo comunicar con el servidor.'
            });
        });

    });
}
}); // <-- Este es el cierre del 'DOMContentLoaded'
