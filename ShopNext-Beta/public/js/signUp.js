form.addEventListener("submit", (event) => {
  document.querySelectorAll(".error-msg").forEach(el => el.remove());

  const nombre = document.getElementById("nombre").value.trim();
  const correo = document.getElementById("correo").value.trim();
  const clave = document.getElementById("clave").value.trim();

  let hasError = false;

  const mostrarError = (input, mensaje) => {
    const p = document.createElement("p");
    p.textContent = mensaje;
    p.classList.add("error-msg");
    p.style.color = "red";
    p.style.marginTop = "5px";
    input.insertAdjacentElement("afterend", p);
  };

  if (!nombre) {
    mostrarError(document.getElementById("nombre"), "El nombre es obligatorio.");
    hasError = true;
  }

  if (!correo) {
    mostrarError(document.getElementById("correo"), "El correo es obligatorio.");
    hasError = true;
  }

  if (!clave) {
    mostrarError(document.getElementById("clave"), "La contraseña es obligatoria.");
    hasError = true;
  }

  // ⚠️ Solo prevenimos el envío si hay errores
  if (hasError) event.preventDefault();
});
