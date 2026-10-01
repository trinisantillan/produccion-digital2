<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    exit("No autorizado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["puntos"])) {
    $servidor = "localhost";
    $usuario  = "root";
    $password = "";            
    $base_datos = "neurohabits";

    $conexion = new mysqli($servidor, $usuario, $password, $base_datos);

    if ($conexion->connect_error) {
        die("Fallo: " . $conexion->connect_error);
    }

    $email = $_SESSION['usuario'];
    $puntos = intval($_POST["puntos"]);

    $stmt = $conexion->prepare("INSERT INTO resultados_quiz (usuario_email, puntos) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("si", $email, $puntos);
        $stmt->execute();
        $stmt->close();
        echo "Guardado";
    }

    $conexion->close();
}
?>