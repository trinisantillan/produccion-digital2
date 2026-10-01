<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | NeuroHabits</title>
    <link href="css/estilos.css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
</head>
<body>

  <header class="header-principal">
       <div class="header-top">
           <img src="imagenes/logoneuro.webp" alt="Logo NeuroHabits">
           <h1>NeuroHabits</h1>
       </div>
       
       <nav class="nav-principal">
           <ul class="nav-menu">
               <li><a href="index.php">Inicio</a></li> 
               <li><a href="que.php">¿Qué es?</a></li>
               <li><a href="cc.php">Ciencias Cognitivas</a></li>
               <li><a href="estudio.php">Estudio y memoria</a></li>
               <li><a href="preguntas.php">FAQ</a></li>
               <li><a href="quiz.php">Quiz</a></li> 
               <li><a href="login.php" class="active">Iniciar sesión</a></li>
           </ul>
       </nav>
   </header>

   <main class="contenedor-registro">
    <h2>Ingresá a tu cuenta</h2>
    
    <form action="procesar_login.php" method="POST">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn-registro">Ingresar</button>
    </form>

    <p style="text-align: center; margin-top: 20px; color: #334155;">
        ¿No tenés cuenta todavía? <a href="registro.php" style="color: #00bcd4; font-weight: bold;">Registrate acá</a>
    </p>
</main>

    <footer class="footer-principal">
        <div class="contenedor-footer">
            <div class="contacto-footer">
                <div class="linea-contacto">
                    <img src="imagenes/insta.png" alt="Instagram" class="icono-footer">
                    <span>@NeuroHabits</span>
                </div>
                <div class="linea-contacto">
                    <img src="imagenes/mail.png" alt="Email" class="icono-footer">
                    <span>neurohabits@gmail.com</span>
                </div>
                <div class="linea-contacto">
                    <img src="imagenes/tele.png" alt="Teléfono" class="icono-footer">
                    <span>+54 11 5555 1234</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>