document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('profile-form');
    const editButton = document.getElementById('edit-profile-btn');
    const saveButton = form.querySelector('.btn-save');
    const cancelButton = document.getElementById('cancel-edit-btn');
    
    const formInputs = form.querySelectorAll('input, select');

    // --- MODO EDICIÓN ---
    if (editButton) {
        editButton.addEventListener('click', function() {
            // 1. Habilitamos todos los campos para que se puedan editar.
            formInputs.forEach(input => {
                input.disabled = false;
            });
    
            // ¡IMPORTANTE! Ya NO borramos el contenido de los campos.
    
            // 2. Cambiamos los botones.
            this.style.display = 'none';
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
        });
    }

    // --- MODO LECTURA (Cancelar) ---
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            // Al cancelar, simplemente recargamos la página.
            // Esto descartará cualquier cambio y restaurará los datos originales.
            location.reload(); 
        });
    }

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