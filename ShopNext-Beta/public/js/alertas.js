document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const status = params.get('status');
  const error = params.get('error');

const mensajes = {

  // Registro
    ok: ['success', '¡Éxito!', 'Cliente registrado correctamente.'],
    correo: ['error', 'Correo duplicado', 'El correo ya está registrado.'],
    conexion: ['error', 'Error de conexión', 'No se pudo conectar a la base de datos.'],
    registro_usuario: ['error', 'Error', 'Error al registrar el usuario.'],
    registro_cliente: ['error', 'Error', 'Error al registrar el cliente.'],

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
    no_existe: ['info', 'Correo no encontrado', 'Ese correo no está registrado.'],

    // Logout
    logout_ok: ['success', 'Sesión Finalizada', 'Has cerrado sesión correctamente.'],

    // Tiempo expirado
    sesion_expirada: ['info', 'Tu sesión ha expirado', 'Por favor, inicia sesión de nuevo.'],

    // Verificar correo
    verificar_correo: ['info', 'Casi listo...', 'Te hemos enviado un correo. Por favor, verifica tu cuenta para poder iniciar sesión.'],
    verificado_ok: ['success', '¡Cuenta Verificada!', 'Tu cuenta ha sido verificada. ya puedes iniciar sesión.'],
    token_invalido: ['error', 'Error', 'El enlace de verificación no es válido o ya ha expirado.'],
    no_verificado: ['warning', 'Cuenta no verificada', 'Revisa tu correo y completa la verificación para poder entrar.'],
    update_failed: ['error', 'Error del Servidor', 'No pudimos actualizar tu estado. Por favor, intenta de nuevo.']
    
  };

  const key = status || error;
  if (mensajes[key]) {
    const [icon, title, text] = mensajes[key];
    Swal.fire({ icon, title, text }).then(() => {
      window.history.replaceState(null, "", window.location.pathname);
    });
  }
});
