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

document.addEventListener("DOMContentLoaded", () => {
    const formContact = document.getElementById("formContact");

    if (formContact) {
        formContact.addEventListener("submit", function(event) {

            const nombre = document.getElementById("nombre").value.trim();
            const correo = document.getElementById("correo").value.trim();
            const telefono = document.getElementById("telefono").value.trim();
            const mensaje = document.getElementById("mensaje").value.trim();

            // Validación de campos vacíos
            if (nombre === "" || correo === "" || mensaje === "") {
                alert("Por favor completa todos los campos obligatorios.");
                return;
            }

            // Validación de correo electrónico
            if (!correo.includes("@") || !correo.includes(".")) {
                alert("Por favor ingresa un correo válido.");
                return;
            }

            alert("¡Mensaje enviado con éxito!");

        });
    }
});
