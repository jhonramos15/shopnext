<?php
include 'conexion.php'; // Tu archivo de conexión a la BD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion=?, token_expiracion=? WHERE correo=?");
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        $link = "http://localhost/shopnexs/recuperar.php?token=$token";

        // ENVIAR CORREO (usando mail o PHPMailer)
        $asunto = "Recupera tu contraseña en ShopNexs";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n$link";
        $cabeceras = "From: no-responder@shopnexs.com";

        mail($email, $asunto, $mensaje, $cabeceras);
        echo "Correo enviado";
    } else {
        echo "Correo no registrado";
    }
}
?>