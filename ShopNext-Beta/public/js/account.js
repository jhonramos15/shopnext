let currentSlide = 0;
    function slide(direction) {
      const slider = document.getElementById('slider');
      const totalSlides = slider.children.length;
      currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
      slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Cierra el menú si se da clic fuera
window.onclick = function(event) {
  if (!event.target.matches('.user-icon')) {
    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown && dropdown.style.display === "block") {
      dropdown.style.display = "none";
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formEditarPerfil");

  if (form) {
    form.addEventListener("submit", function (e) {
      const nombre = document.getElementById("nombre").value.trim();
      const correo = document.getElementById("correo").value.trim();
      const direccion = document.getElementById("direccion").value.trim();
      const actualPass = document.getElementById("actual_contrasena").value.trim();
      const nuevaPass = document.getElementById("nueva_contrasena").value.trim();
      const confirmarPass = document.getElementById("confirmar_contrasena").value.trim();

      // Validar campos vacíos
      if (nombre === "" || correo === "" || direccion === "") {
        alert("Por favor, completa todos los campos obligatorios.");
        e.preventDefault();
        return;
      }

      // Validar correo electrónico
      const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!regexCorreo.test(correo)) {
        alert("Por favor, ingresa un correo electrónico válido.");
        e.preventDefault();
        return;
      }

      // Validar contraseña (si se intenta cambiar)
      if (nuevaPass !== "" || confirmarPass !== "") {
        if (nuevaPass.length < 6) {
          alert("La nueva contraseña debe tener al menos 6 caracteres.");
          e.preventDefault();
          return;
        }

        if (nuevaPass !== confirmarPass) {
          alert("Las contraseñas no coinciden.");
          e.preventDefault();
          return;
        }

        if (actualPass === "") {
          alert("Debes ingresar la contraseña actual para cambiarla.");
          e.preventDefault();
          return;
        }
      }
    });
  }
});
