<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NeuroHabits | Hackeá tu Mente</title>
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
           <input type="checkbox" id="menu-toggle" class="menu-checkbox">
           <label for="menu-toggle" class="hamburger-btn">
               <span></span>
               <span></span>
               <span></span>
           </label>

           <ul class="nav-menu">
               <li><a href="index.html">Inicio</a></li> 
               <li><a href="que.html">¿Qué es?</a></li>
               <li><a href="cc.html">Ciencias Cognitivas</a></li>
               <li><a href="estudio.html">Estudio y memoria</a></li>
               <li><a href="preguntas.html">FAQ</a></li>
               <li><a href="quiz.html">Quiz</a></li> 
                <li><a href="registro.html" class="active">Iniciar sesión</a> </li>
           </ul>
       </nav>
   </header>

   <main class="contenedor-registro">
    <h2>Crea tu cuenta</h2>
    
    <form action="procesar_registro.php" method="POST">
    
        <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="btn-registro">Registrarme</button>

    </form>
</main>