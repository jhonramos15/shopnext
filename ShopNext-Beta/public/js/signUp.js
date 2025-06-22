document.addEventListener("DOMContentLoaded", () => {
  document.querySelector(".hamburger").addEventListener("click", () => {
    document.getElementById("navMenu").classList.toggle("active");
  });

  // tu código de validación del formulario aquí...
});



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
        const datos = new FormData();
        datos.append("nombre", nombre);
        datos.append("correo", correo);
        datos.append("clave", clave);

        fetch("../../core/registroController.php", {
          method: "POST",
          body: datos
        })
        .then(response => response.text())
        .then(respuesta => {
          if (respuesta.includes("registrado")) {
            Swal.fire({
              icon: "error",
              title: "Correo en uso",
              text: respuesta,
              confirmButtonColor: "#d33"
            });
          } else {
            Swal.fire({
              icon: "success",
              title: "¡Bien!",
              text: respuesta,
              confirmButtonColor: "#3085d6"
            });
            signUpForm.reset();
          }
        })
        .catch(error => {
          Swal.fire({
            icon: "error",
            title: "Error de red",
            text: "No se pudo completar el registro.",
            footer: error,
            confirmButtonColor: "#d33"
          });
        });
      }
    });
  }
});