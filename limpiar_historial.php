<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$servidor = "localhost";
$usuario  = "root";
$password = "";            
$base_datos = "neurohabits";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Fallo: " . $conexion->connect_error);
}

$email = $_SESSION['usuario'];

$stmt = $conexion->prepare("DELETE FROM resultados_quiz WHERE usuario_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->close();
$conexion->close();

header("Location: perfil.php");
exit();
?>