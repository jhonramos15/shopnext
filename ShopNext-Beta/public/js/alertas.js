  document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');

    const mensajes = {
      ok: ['success', '¡Éxito!', 'Perfil actualizado correctamente'],
      nombre_invalido: ['error', 'Nombre corto', 'El nombre es muy corto'],
      error: ['error', 'Error', 'No se pudo actualizar el perfil'],
      sin_cambios: ['info', 'Sin cambios', 'No hay cambios para guardar'],
      correo_invalido: ['error', 'Correo inválido', 'Ingresa un correo válido'],
      datos_invalidos: ['error', 'Datos inválidos', 'Nombre o dirección muy cortos'],
      pass_incorrecta: ['error', 'Contraseña incorrecta', 'La contraseña actual no coincide'],
      pass_no_coincide: ['error', 'Error', 'Las nuevas contraseñas no coinciden'],
    };

    if (mensajes[status]) {
      const [icon, title, text] = mensajes[status];
      Swal.fire({ icon, title, text }).then(() => {
        // Borra el parámetro de la URL para que no se repita al recargar
        window.history.replaceState(null, "", window.location.pathname);
      });
    }
  });