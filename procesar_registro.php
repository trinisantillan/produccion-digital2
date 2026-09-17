<?php

$servidor = "localhost";
$usuario  = "root";
$password = "";            
$base_datos = "neurohabits";


$conexion = new mysqli($servidor, $usuario, $password, $base_datos);


if ($conexion->connect_error) {
    die("Fallo en la conexión a la base de datos: " . $conexion->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    if (!empty($_POST["nombre"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        
        $nombre = trim($_POST["nombre"]);
        $email  = trim($_POST["email"]);
        $pass   = $_POST["password"];

        
        $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

        
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $email, $pass_encriptada);

            if ($stmt->execute()) {
                
                echo "<h2 style='font-family: sans-serif; text-align: center; margin-top: 50px; color: #00bcd4;'>¡Cuenta creada con éxito!</h2>";
                echo "<p style='font-family: sans-serif; text-align: center;'>Redirigiendo a la página principal...</p>";
                header("refresh:2;url=index.html");
            } else {
                
                echo "<p style='font-family: sans-serif; color: red; text-align: center;'>Error al registrar: el correo electrónico ya está registrado.</p>";
                echo "<p style='text-align: center;'><a href='registro.html'>Volver a intentar</a></p>";
            }

            $stmt->close();
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }

    } else {
        echo "<p style='font-family: sans-serif; color: red; text-align: center;'>Por favor, completa todos los campos requeridos.</p>";
    }
}

$conexion->close();
?>