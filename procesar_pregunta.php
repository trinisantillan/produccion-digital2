<?php
session_start();

$servidor = "localhost";
$usuario  = "root";
$password = "";            
$base_datos = "neurohabits";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Fallo en la conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre  = trim($_POST["nombre"] ?? '');
    $email   = trim($_POST["email"] ?? '');
    $mensaje = trim($_POST["mensaje"] ?? $_POST["pregunta"] ?? '');

    if (!empty($nombre) && !empty($email) && !empty($mensaje)) {
        $stmt = $conexion->prepare("INSERT INTO preguntas_contacto (nombre, email, mensaje) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $email, $mensaje);

            if ($stmt->execute()) {
                echo "<h2 style='font-family: sans-serif; text-align: center; margin-top: 50px; color: #00bcd4;'>¡Pregunta enviada con éxito!</h2>";
                echo "<p style='font-family: sans-serif; text-align: center;'>Gracias por contactarnos. Redirigiendo...</p>";
                header("refresh:2;url=preguntas.php");
                exit();
            } else {
                echo "<p style='font-family: sans-serif; color: red; text-align: center;'>Error al enviar: " . $stmt->error . "</p>";
                echo "<p style='text-align: center;'><a href='preguntas.php'>Volver a intentar</a></p>";
            }

            $stmt->close();
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }
    } else {
        echo "<p style='font-family: sans-serif; color: red; text-align: center; margin-top: 50px;'>Por favor, completá todos los campos.</p>";
        echo "<p style='text-align: center;'><a href='preguntas.php'>Volver</a></p>";
    }
}

$conexion->close();
?>