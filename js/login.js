document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("formLogin");

    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevenir el envío del formulario

            const correo = document.getElementById("correo").value.trim();
            const clave = document.getElementById("clave").value.trim();

            console.log("Correo:", correo); // Para ver lo que se captura
            console.log("Contraseña:", clave); // Para ver lo que se captura
            
            if (correo === "" || clave === "") {
                alert("Por favor completa todos los campos.");
                return; // Esto debería detener la ejecución si los campos están vacíos
            }

            if (!correo.includes("@") || !correo.includes(".")) {
                alert("Por favor ingresa un correo válido.");
                return;
            }

            alert("¡Inicio de sesión exitoso!");
        });
    }
});
