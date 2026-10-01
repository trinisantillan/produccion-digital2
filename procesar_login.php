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
    $email = trim($_POST["email"] ?? '');
    $pass  = $_POST["password"] ?? '';

    if (!empty($email) && !empty($pass)) {
      
        $stmt = $conexion->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario_data = $resultado->fetch_assoc();

            
            if (password_verify($pass, $usuario_data['password'])) {
            
                $_SESSION['usuario'] = $usuario_data['email'];
                $_SESSION['nombre']  = $usuario_data['nombre'];

                header("Location: index.php");
                exit();
            } else {
                echo "<p style='font-family: sans-serif; color: red; text-align: center; margin-top: 50px;'>Contraseña incorrecta.</p>";
                echo "<p style='text-align: center;'><a href='login.php'>Volver a intentar</a></p>";
            }
        } else {
            echo "<p style='font-family: sans-serif; color: red; text-align: center; margin-top: 50px;'>No existe una cuenta con ese correo.</p>";
            echo "<p style='text-align: center;'><a href='registro.php'>Crear una cuenta</a> | <a href='login.php'>Reintentar</a></p>";
        }

        $stmt->close();
    } else {
        echo "<p style='font-family: sans-serif; color: red; text-align: center; margin-top: 50px;'>Completá todos los campos.</p>";
    }
}

$conexion->close();
?>