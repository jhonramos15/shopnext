console.log("Enviando datos...");1


document.getElementById("formSignUp").addEventListener("submit", function(e) {
    e.preventDefault() // Prevenir envío automático

    // Obtener valores
    const nombre = document.getElementById("nombre").value
    const correo = document.getElementById("correo").value
    const clave = document.getElementById("clave").value

    // Validaciones básicas
    if (nombre === "" || correo === "" || clave === "") {
        alert("Por favor, completa todos los campos.")
        return
    }

    // Crear datos a enviar
    const datos = new FormData()
    datos.append("nombre", nombre)
    datos.append("correo", correo)
    datos.append("clave", clave)

    // Enviar con fetch
    fetch("../php/registro.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(respuesta => {
        alert(respuesta) // Mostrar respuesta del servidor
        // Opcional: limpiar formulario
        document.getElementById("formSignUp").reset();
    })
    .catch(error => {
        alert("Error en el registro: " + error);
    })
})
