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
