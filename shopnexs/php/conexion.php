<?php

    $conect = mysqli_connect("localhost", "root", "1234", "shopnexs", "3306");

    if ($conect) {
        echo "✅ || Conexión exitosa. <br/>"; 
    } else {
        echo "❎ || Conexión fallida, intenta de nuevo.";
    }

?>