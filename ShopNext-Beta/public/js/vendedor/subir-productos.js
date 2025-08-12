// En: public/js/vendedor/subirProductos.js

document.addEventListener('DOMContentLoaded', function () {
    const uploadBox = document.querySelector('.image-upload-box');
    const fileInput = document.getElementById('imagen');
    
    // Asegurarnos de que los elementos existen en esta página
    if (uploadBox && fileInput) {
        const uploadText = uploadBox.querySelector('.upload-content p');

        // Activa el input de archivo al hacer clic en el área
        uploadBox.addEventListener('click', () => {
            fileInput.click();
        });

        // Muestra el nombre del archivo seleccionado
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                uploadText.innerHTML = `<span>Archivo:</span> ${fileInput.files[0].name}`;
            } else {
                uploadText.innerHTML = `<span>Seleccionar archivo</span> o arrástralo aquí.`;
            }
        });
    }
});