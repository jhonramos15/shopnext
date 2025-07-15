document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('profile-form');
    const editButton = document.getElementById('edit-profile-btn');
    const saveButton = form.querySelector('.btn-save');
    const cancelButton = document.getElementById('cancel-edit-btn');
    const formInputs = form.querySelectorAll('input, select');

    // --- MODO EDICIÓN ---
    editButton.addEventListener('click', function() {
        // Habilitamos todos los campos del formulario
        formInputs.forEach(input => {
            input.disabled = false;
        });

        // === ¡AQUÍ ESTÁ LA NUEVA LÓGICA! ===
        // Limpiamos el valor de cada campo, excepto los ocultos o los de tipo 'file'.
        form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="password"]').forEach(input => {
            input.value = ''; 
        });
        // También podemos resetear el selector de género si queremos
        form.querySelector('select[name="genero"]').selectedIndex = 0;
        // ===================================

        // Ocultamos el botón "Editar" y mostramos los de "Guardar" y "Cancelar"
        this.style.display = 'none';
        saveButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
    });

    // --- MODO LECTURA (Cancelar) ---
    cancelButton.addEventListener('click', function() {
        // Si cancela, lo mejor es recargar la página para restaurar los datos originales.
        location.reload(); 
    });

    // --- Lógica para la previsualización de la foto (sin cambios) ---
    const profilePicInput = document.getElementById('profile-pic-upload');
    const profilePicImage = document.getElementById('profile-pic');
    if (profilePicInput && profilePicImage) {
        profilePicInput.onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                profilePicImage.src = URL.createObjectURL(file);
            }
        };
    }
});

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