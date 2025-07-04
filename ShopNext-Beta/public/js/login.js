document.addEventListener("DOMContentLoaded", () => {
  const passwordInput = document.getElementById('password');
  const togglePasswordButton = document.getElementById('togglePassword');
  
  if (passwordInput && togglePasswordButton) {
    
    // --- NUEVO: Ocultar el botón por defecto ---
    togglePasswordButton.classList.add('hidden');

    // --- NUEVO: Evento para escuchar mientras escribes ---
    passwordInput.addEventListener('input', () => {
      // Si el campo de contraseña tiene texto...
      if (passwordInput.value.length > 0) {
        togglePasswordButton.classList.remove('hidden'); // Muestra el botón
      } else {
        togglePasswordButton.classList.add('hidden'); // Oculta el botón
      }
    });

    // --- Se mantiene igual: Funcionalidad de click para mostrar/ocultar ---
    togglePasswordButton.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  }

  // --- Se mantiene igual: Funcionalidad para VALIDAR el formulario de login ---
  const loginForm = document.getElementById("formLogin");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      const correo = document.getElementById("correo").value.trim();
      const clave = document.getElementById("password").value.trim();
      
      if (correo === "" || clave === "") {
        event.preventDefault();
        alert("Por favor completa todos los campos.");
        return;
      }

      if (!correo.includes("@") || !correo.includes(".")) {
        event.preventDefault();
        alert("Por favor ingresa un correo válido.");
        return;
      }
    });
  }
});