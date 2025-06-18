document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const status = params.get('status');
  const error = params.get('error');

  const mensajes = {
    // Login
    vacio: ['info', 'Campos vacíos', 'Por favor llena todos los campos.'],
    usuario: ['error', 'Usuario no encontrado', 'El correo no está registrado.'],
    clave: ['error', 'Contraseña incorrecta', 'La contraseña no coincide.'],

    // Perfil
    ok: ['success', '¡Éxito!', 'Perfil actualizado correctamente'],
    nombre_invalido: ['error', 'Nombre corto', 'El nombre es muy corto'],
    error: ['error', 'Error', 'No se pudo actualizar el perfil'],
    sin_cambios: ['info', 'Sin cambios', 'No hay cambios para guardar'],
    correo_invalido: ['error', 'Correo inválido', 'Ingresa un correo válido'],
    datos_invalidos: ['error', 'Datos inválidos', 'Nombre o dirección muy cortos'],
    pass_incorrecta: ['error', 'Contraseña incorrecta', 'La contraseña actual no coincide'],
    pass_no_coincide: ['error', 'Error', 'Las nuevas contraseñas no coinciden'],

    // Olvidé contraseña
    correo_enviado: ['success', 'Correo enviado', 'Revisa tu bandeja de entrada.'],
    error_envio: ['error', 'Error', 'No se pudo enviar el correo.'],
    no_existe: ['info', 'Correo no encontrado', 'Ese correo no está registrado.']
  };

  const key = status || error;
  if (mensajes[key]) {
    const [icon, title, text] = mensajes[key];
    Swal.fire({ icon, title, text }).then(() => {
      window.history.replaceState(null, "", window.location.pathname);
    });
  }
});
