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
$nombre = $_SESSION['nombre'] ?? 'Usuario';

$sql_quiz = "SELECT puntos, total, fecha FROM resultados_quiz WHERE usuario_email = ? ORDER BY fecha DESC";
$stmt_quiz = $conexion->prepare($sql_quiz);
$stmt_quiz->bind_param("s", $email);
$stmt_quiz->execute();
$resultado_quiz = $stmt_quiz->get_result();

$sql_max = "SELECT MAX(puntos) as mejor_puntaje FROM resultados_quiz WHERE usuario_email = ?";
$stmt_max = $conexion->prepare($sql_max);
$stmt_max->bind_param("s", $email);
$stmt_max->execute();
$max_data = $stmt_max->get_result()->fetch_assoc();
$mejor_puntaje = $max_data['mejor_puntaje'];

if ($mejor_puntaje === null) {
    $nivel = "🌱 Sin evaluar";
    $color_nivel = "#94a3b8";
} elseif ($mejor_puntaje == 8) {
    $nivel = "🏆 NeuroMaster";
    $color_nivel = "#ffd700";
} elseif ($mejor_puntaje >= 4) {
    $nivel = "⚡ Explorador Cognitivo";
    $color_nivel = "#00bcd4";
} else {
    $nivel = "🌱 Aprendiz Neural";
    $color_nivel = "#4ade80";
}
$sql_faq = "SELECT mensaje, fecha FROM preguntas_contacto WHERE email = ? ORDER BY fecha DESC";
$stmt_faq = $conexion->prepare($sql_faq);
$stmt_faq->bind_param("s", $email);
$stmt_faq->execute();
$resultado_faq = $stmt_faq->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($nombre); ?> | NeuroHabits</title>
    <link href="css/estilos.css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column; margin: 0;">

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
               <li><a href="perfil.php" class="active" style="color: #64b5f6; font-weight: bold;">Hola, <?php echo htmlspecialchars($nombre); ?></a></li>
               <li><a href="logout.php" style="color: #ff7675;">Salir</a></li>
           </ul>
       </nav>
   </header>

   <main class="contenedor-estudio" style="padding: 40px 20px; max-width: 850px; margin: auto; flex: 1; width: 100%; box-sizing: border-box;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h2 style="color: #003662; margin: 0; font-size: 1.8rem;">Perfil de <?php echo htmlspecialchars($nombre); ?></h2>
                <span style="display: inline-block; margin-top: 6px; font-weight: 700; color: <?php echo $color_nivel; ?>; background: #0f1c2e; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">
                    <?php echo $nivel; ?>
                </span>
            </div>
            <a href="logout.php" style="background: #e74c3c; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Cerrar sesión</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 35px;">
            <div style="background: #0f1c2e; padding: 18px; border-radius: 10px; text-align: center; border-bottom: 4px solid #00bcd4;">
                <span style="font-size: 0.85rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Intentos Quiz</span>
                <p style="margin: 8px 0 0 0; font-size: 1.8rem; font-weight: 700; color: #fff;"><?php echo $resultado_quiz->num_rows; ?></p>
            </div>
            <div style="background: #0f1c2e; padding: 18px; border-radius: 10px; text-align: center; border-bottom: 4px solid #00e676;">
                <span style="font-size: 0.85rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Mejor Puntaje</span>
                <p style="margin: 8px 0 0 0; font-size: 1.8rem; font-weight: 700; color: #00e676;">
                    <?php echo ($mejor_puntaje !== null) ? $mejor_puntaje . " / 8" : "-"; ?>
                </p>
            </div>
            <div style="background: #0f1c2e; padding: 18px; border-radius: 10px; text-align: center; border-bottom: 4px solid #f59e0b;">
                <span style="font-size: 0.85rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Dudas Enviadas</span>
                <p style="margin: 8px 0 0 0; font-size: 1.8rem; font-weight: 700; color: #fff;"><?php echo $resultado_faq->num_rows; ?></p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #cbd5e1; padding-bottom: 8px;">
            <h3 style="color: #002b49; margin: 0;">🧠 Intentos en el Quiz Cognitivo</h3>
            <?php if ($resultado_quiz->num_rows > 0): ?>
                <a href="limpiar_historial.php" onclick="return confirm('¿Seguro que querés reiniciar tu historial del quiz?');" style="color: #ef4444; font-size: 0.8rem; text-decoration: underline; font-weight: 600;">Restablecer historial</a>
            <?php endif; ?>
        </div>
        
        <?php if ($resultado_quiz->num_rows > 0): ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; color: white; background: #0f1c2e; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background-color: #172a45; text-align: left;">
                        <th style="padding: 14px 16px;">Intento</th>
                        <th style="padding: 14px 16px;">Puntaje</th>
                        <th style="padding: 14px 16px;">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $intento = $resultado_quiz->num_rows;
                    while ($fila = $resultado_quiz->fetch_assoc()): 
                    ?>
                        <tr style="border-bottom: 1px solid #233c63;">
                            <td style="padding: 14px 16px;">Intento #<?php echo $intento--; ?></td>
                            <td style="padding: 14px 16px; font-weight: bold; color: #00e676;">
                                <?php echo $fila['puntos']; ?> / <?php echo $fila['total']; ?>
                            </td>
                            <td style="padding: 14px 16px; color: #94a3b8;"><?php echo $fila['fecha']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="margin-top: 15px; color: #475569;">Todavía no completaste el Quiz. <a href="quiz.php" style="color: #0077b6; font-weight: bold;">¡Hacé tu primer intento acá!</a></p>
        <?php endif; ?>

        <h3 style="color: #002b49; margin-top: 45px; border-bottom: 2px solid #cbd5e1; padding-bottom: 8px;">📩 Preguntas que enviaste al equipo</h3>
        
        <?php if ($resultado_faq->num_rows > 0): ?>
            <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 15px;">
                <?php while ($pregunta = $resultado_faq->fetch_assoc()): ?>
                    <div style="background: #0f1c2e; padding: 18px 20px; border-radius: 8px; border-left: 5px solid #00bcd4; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                        <p style="color: #ffffff; margin: 0 0 8px 0; font-size: 1.05rem; font-weight: 500;">
                            "<?php echo htmlspecialchars($pregunta['mensaje']); ?>"
                        </p>
                        <span style="font-size: 0.85rem; color: #94a3b8;">
                            Enviada el: <?php echo $pregunta['fecha']; ?>
                        </span>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="margin-top: 15px; color: #475569;">No realizaste ninguna pregunta todavía. Si tenés dudas, <a href="preguntas.php" style="color: #0077b6; font-weight: bold;">dejá tu consulta acá</a>.</p>
        <?php endif; ?>

          </main>

<footer class="footer-principal" style="width: 100%; margin-top: auto;">
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
<?php 
$stmt_quiz->close();
$stmt_max->close();
$stmt_faq->close();
$conexion->close();
?>