document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);

    // ✅ Muestra la alerta de éxito si la URL es "?status=verificado_ok"
    if (urlParams.get('status') === 'verificado_ok') {
        Swal.fire({
            icon: 'success',
            title: '¡Cuenta Verificada!',
            text: 'Tu cuenta ha sido verificada exitosamente. Ahora puedes iniciar sesión.',
            confirmButtonText: '¡Genial!'
        });
    }

    // Muestra las alertas de error
    const error = urlParams.get('error');
    if (error) {
        let message = 'Tus credenciales son incorrectas. Por favor, inténtalo de nuevo.';
        if (error === 'no_verificado') {
            message = 'Tu cuenta aún no ha sido verificada. Por favor, revisa tu correo electrónico.';
        } else if (error === 'token_invalido') {
            message = 'El enlace de verificación no es válido o ya ha expirado.';
        }
        Swal.fire({
            icon: 'error',
            title: 'Error al Iniciar Sesión',
            text: message
        });
    }

    // --- Lógica para el botón de mostrar/ocultar contraseña ---
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    if (passwordInput && togglePasswordButton) {
        // Tu código existente para el ojo de la contraseña va aquí y funcionará igual.
        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});