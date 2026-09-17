<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>NeuroHabits</title>
        <link href="css/estilos.css" rel="stylesheet"/>
        <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no" />
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
    <li><a href="quiz.html" class="active">Quiz</a></li> 
    <li><a href="registro.html">Iniciar sesión</a> </li>
        </ul>

    </nav>
</header>

       <main class="main-quiz">
        <div class="presentacion-color">
    <h1>🧠 Quiz: ¿Cuánto sabés de tu cerebro?</h1>
    <h2>Sumá 1 punto por cada respuesta correcta y descubrí tu resultado al final.</h2>
    </div>
    
<div id="quiz">
    <div class="pregunta">
        <h3>1. Estás estudiando y te distraés con el celular. ¿Qué función del cerebro está fallando más?</h3>
        <ul>
            <li><label><input type="radio" name="p1" value="a" data-correcta="false"> a) Memoria</label></li>
            <li><label><input type="radio" name="p1" value="b" data-correcta="true"> b) Atención</label></li>
            <li><label><input type="radio" name="p1" value="c" data-correcta="false"> c) Lenguaje</label></li>
        </ul>
        <p class="feedback" id="feedback1"></p>
    </div>

    <div class="pregunta">
        <h3>2. Después de dormir bien, recordás mejor lo que estudiaste. ¿Por qué?</h3>
        <ul>
            <li><label><input type="radio" name="p2" value="a" data-correcta="true"> a) Porque el sueño ayuda a fijar la información en la memoria</label></li>
            <li><label><input type="radio" name="p2" value="b" data-correcta="false"> b) Porque el cerebro aumenta su tamaño físico</label></li>
            <li><label><input type="radio" name="p2" value="c" data-correcta="false"> c) Porque solo recordamos lo que soñamos</label></li>
        </ul>
        <p class="feedback" id="feedback2"></p>
    </div>

    <div class="pregunta">
        <h3>3. Practicar varias veces algo (como resumir o explicar) ayuda porque…</h3>
        <ul>
            <li><label><input type="radio" name="p3" value="a" data-correcta="false"> a) El cerebro entra en estado de fatiga absoluta</label></li>
            <li><label><input type="radio" name="p3" value="b" data-correcta="false"> b) El dato se borra para dejar espacio a otros nuevos</label></li>
            <li><label><input type="radio" name="p3" value="c" data-correcta="true"> c) Se estimula la plasticidad y se fortalecen las conexiones neuronales</label></li>
        </ul>
        <p class="feedback" id="feedback3"></p>
    </div>

    <div class="pregunta">
        <h3>4. Si hacés pausas mientras estudiás (tipo Pomodoro), lográs…</h3>
        <ul>
            <li><label><input type="radio" name="p4" value="a" data-correcta="false"> a) Romper el ritmo y perder tiempo de estudio</label></li>
            <li><label><input type="radio" name="p4" value="b" data-correcta="true"> b) Limpiar la fatiga cognitiva y mejorar la concentración posterior</label></li>
            <li><label><input type="radio" name="p4" value="c" data-correcta="false"> c) Olvidar lo aprendido en el bloque anterior</label></li>
        </ul>
        <p class="feedback" id="feedback4"></p>
    </div>

    <div class="pregunta">
        <h3>5. ¿Qué hemisferio del cerebro domina a las personas que son puramente creativas y artísticas?</h3>
        <ul>
            <li><label><input type="radio" name="p5" value="a" data-correcta="false"> a) El hemisferio derecho de forma exclusiva</label></li>
            <li><label><input type="radio" name="p5" value="b" data-correcta="false"> b) El hemisferio izquierdo de forma exclusiva</label></li>
            <li><label><input type="radio" name="p5" value="c" data-correcta="true"> c) Ninguno, la creatividad requiere que ambos hemisferios trabajen en conjunto</label></li>
        </ul>
        <p class="feedback" id="feedback5"></p>
    </div>

    <div class="pregunta">
        <h3>6. ¿Qué porcentaje de nuestra capacidad cerebral utilizamos realmente en el día a día?</h3>
        <ul>
            <li><label><input type="radio" name="p6" value="a" data-correcta="true"> a) El 100%, todas las áreas muestran actividad en las neuroimágenes</label></li>
            <li><label><input type="radio" name="p6" value="b" data-correcta="false"> b) Solo el 10%, el resto está inactivo esperando a ser desarrollado</label></li>
            <li><label><input type="radio" name="p6" value="c" data-correcta="false"> c) El 50%, dividido exactamente según la lógica o la intuición</label></li>
        </ul>
        <p class="feedback" id="feedback6"></p>
    </div>

    <div class="pregunta">
        <h3>7. ¿Por qué sentimos un "bajón" de energía unas horas después de tomar café?</h3>
        <ul>
            <li><label><input type="radio" name="p7" value="a" data-correcta="false"> a) Porque la cafeína destruye las moléculas de glucosa</label></li>
            <li><label><input type="radio" name="p7" value="b" data-correcta="false"> b) Porque el cerebro apaga temporalmente el hipocampo</label></li>
            <li><label><input type="radio" name="p7" value="c" data-correcta="true"> c) Porque la adenosina acumulada inunda de golpe los receptores que estaban bloqueados</label></li>
        </ul>
        <p class="feedback" id="feedback7"></p>
    </div>

    <div class="pregunta">
        <h3>8. Cuando aprendés un concepto nuevo o modificás un hábito, tu cerebro…</h3>
        <ul>
            <li><label><input type="radio" name="p8" value="a" data-correcta="true"> a) Se reconfigura físicamente creando nuevas conexiones</label></li>
            <li><label><input type="radio" name="p8" value="b" data-correcta="false"> b) Se mantiene estático e inmutable desde la infancia</label></li>
            <li><label><input type="radio" name="p8" value="c" data-correcta="false"> c) Descarta información antigua de forma aleatoria</label></li>
        </ul>
        <p class="feedback" id="feedback8"></p>
    </div>
    
    <button id="btnResultado">Ver mi resultado</button>
    <div id="resultado-final"></div>
    
    <div class="resultados">
        <h4>Resultados</h4>
        <p>0-3 puntos: Podés mejorar tus hábitos 🧠</p>
        <p>4-6 puntos: Vas bien, ¡seguí así! 💪</p>
        <p>7-8 puntos: ¡Modo cerebro experto! 🏆</p>
    </div>
</div>

</main>

<script>
    document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const nombre = this.name;
            const numero = nombre.replace('p', '');
            const feedback = document.getElementById('feedback' + numero);
            
            if (this.dataset.correcta === 'true') {
                feedback.textContent = '✅ ¡Correcto!';
                feedback.style.color = 'green';
            } else {
                feedback.textContent = '❌ Incorrecto, intentá de nuevo.';
                feedback.style.color = 'red';
            }
        });
    });

    document.getElementById('btnResultado').addEventListener('click', function() {
        const preguntas = ['p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8'];
        let respondidas = 0;
        let correctas = 0;

        preguntas.forEach(function(nombre) {
            const seleccionada = document.querySelector('input[name="' + nombre + '"]:checked');
            if (seleccionada) {
                respondidas++;
                if (seleccionada.dataset.correcta === 'true') {
                    correctas++;
                }
            }
        });

        if (respondidas < 8) {
            alert('¡Respondé todas las preguntas primero!');
            return;
        }

        let mensaje = '';
        if (correctas <= 3) mensaje = 'Podés mejorar tus hábitos 🧠';
        else if (correctas <= 6) mensaje = '¡Vas bien, seguí así! 💪';
        else mensaje = '¡Modo cerebro experto! 🏆';

        document.getElementById('resultado-final').innerHTML = 
            '<h3>Tu resultado: ' + correctas + '/8</h3><p>' + mensaje + '</p>';
    });
</script>

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