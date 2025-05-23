// Validación Sign-Up
document.addEventListener("DOMContentLoaded", () => {
    const signUpForm = document.getElementById("formSignUp");
  
    if (signUpForm) {
      signUpForm.addEventListener("submit", function (event) {
        event.preventDefault();
  
        let nombre = document.getElementById("nombre").value.trim();
        let correo = document.getElementById("correo").value.trim();
        let clave = document.getElementById("clave").value.trim();
  
        document.querySelectorAll(".error-msg").forEach(el => el.remove());
  
        let hasError = false;
  
        function mostrarError(input, mensaje) {
          let p = document.createElement("p");
          p.textContent = mensaje;
          p.style.color = "red";
          p.classList.add("error-msg");
          input.parentNode.insertBefore(p, input.nextSibling);
        }
  
        if (nombre === "") {
          mostrarError(document.getElementById("nombre"), "El nombre es obligatorio.");
          hasError = true;
        }
  
        if (correo === "") {
          mostrarError(document.getElementById("correo"), "El correo es obligatorio.");
          hasError = true;
        }
  
        if (clave === "") {
          mostrarError(document.getElementById("clave"), "La contraseña es obligatoria.");
          hasError = true;
        }
  
        if (!hasError) {
          alert("¡Cuenta creada exitosamente!");
        }
      });
    }
  });
  