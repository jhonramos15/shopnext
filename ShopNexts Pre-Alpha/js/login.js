document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("formLogin");

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const correo = document.getElementById("correo").value.trim();
      const clave = document.getElementById("clave").value.trim();

      if (correo === "" || clave === "") {
        alert("Por favor completa todos los campos.");
        return;
      }

      // Validación sencilla correo
      if (!correo.includes("@") || !correo.includes(".")) {
        alert("Por favor ingresa un correo válido.");
        return;
      }

      this.submit(); // envía el formulario si todo está bien
    });
  }
});
