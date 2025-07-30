document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-account-form');
    const profilePicUpload = document.getElementById('profile-pic-upload');
    const profilePicPreview = document.getElementById('profile-pic-preview');
    const uploadButton = document.getElementById('upload-button');

    // Previsualizar la imagen seleccionada
    profilePicUpload.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePicPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Subir la imagen al hacer clic en el botón "Cambiar Foto"
    uploadButton.addEventListener('click', function() {
        profilePicUpload.click();
    });


    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        const fileInput = document.getElementById('profile-pic-upload');

        if (fileInput.files[0]) {
            formData.append('foto_perfil', fileInput.files[0]);
        }


        fetch('../../../../controllers/admin/adminAccountController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perfil actualizado con éxito.');
                    if (data.new_image_url) {
                        // Actualizamos la imagen de perfil en la vista previa y en el header
                        document.getElementById('profile-pic-preview').src = '../../../../' + data.new_image_url;
                        document.querySelector('.main-header .profile-pic').src = '../../../../' + data.new_image_url;
                    }
                } else {
                    alert('Error al actualizar el perfil: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            });
    });
});