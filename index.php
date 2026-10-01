<?php session_start(); ?>
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
               <li><a href="index.php" class="active">Inicio</a></li> 
               <li><a href="que.php">¿Qué es?</a></li>
               <li><a href="cc.php">Ciencias Cognitivas</a></li>
               <li><a href="estudio.php">Estudio y memoria</a></li>
               <li><a href="preguntas.php">FAQ</a></li>
               <li><a href="quiz.php">Quiz</a></li> 
               <?php if (isset($_SESSION['usuario'])): ?>
    <li><a href="perfil.php" style="color: #64b5f6; font-weight: bold;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></a></li>
    <li><a href="logout.php" style="color: #ff7675;">Salir</a></li>
<?php else: ?>
    <li><a href="login.php">Iniciar sesión</a></li>
<?php endif; ?>
       </nav>
   </header>

    <section class="hero-premium">
        <div class="hero-content">
            <span class="badge-tecnologico">DIVULGACIÓN CIENTÍFICA</span>
            <h1>Potenciá tu mente.<br>Hackeá tus hábitos.</h1>
            <p>Descubrí cómo funciona el órgano más complejo del cuerpo humano a través de herramientas científicas aplicadas a tu rendimiento y estudio diario.</p>
           <a href="registro.php" class="btn-premium">Empezar a explorar 🧠</a>
        </div>
    </section>

    <main id="explorar">
        
        <section class="seccion-presentacion">
            <div class="texto-presentacion">
                <h2>Tu cerebro está cambiando en este instante</h2>
                <p>Gracias a la <strong>neuroplasticidad</strong>, tu cerebro puede generar nuevas conexiones durante toda la vida. Cada vez que incorporás un nuevo hábito, memorizás un concepto o modificás una rutina, estás rediseñando físicamente tu estructura cerebral.</p>
            </div>
            <div class="imagen-presentacion">
                <img src="imagenes/neurociencias.jpg" alt="Redes neuronales en actividad">
                <span class="copete-imagen">Ilustración tridimensional de una sinapsis generando un impulso eléctrico.</span>
            </div>
        </section>

        <section class="seccion-tarjetas">
            <div class="tarjetas-titulos">
                <h2>Poné a prueba tu curiosidad</h2>
                <p>Tocá sobre las tarjetas para activar los impulsos y revelar datos asombrosos.</p>
            </div>

            <div class="flip-container">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>🧠 ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>El cerebro humano tiene aproximadamente 86 mil millones de neuronas.</p>
                        </div>
                    </div>
                </div>

                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>😴 ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>Mientras dormís, tu cerebro consolida los recuerdos del día.</p>
                        </div>
                    </div>
                </div>

                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>⚡ ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>Las señales nerviosas viajan a 430 km/h.</p>
                        </div>
                    </div>
                </div>

                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>💤 ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>El cerebro sigue activo mientras dormís, procesando información del día.</p>
                        </div>
                    </div>
                </div>

                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>🎯 ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>La técnica Pomodoro mejora la concentración dividiendo el tiempo en bloques de 25 minutos.</p>
                        </div>
                    </div>
                </div>

                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-front">
                            <h3>🔄 ¿Sabías que...?</h3>
                        </div>
                        <div class="flip-back">
                            <p>Cada vez que recordás algo, tu cerebro lo reconstruye, no lo reproduce exactamente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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