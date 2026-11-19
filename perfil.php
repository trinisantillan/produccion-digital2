
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "neurohabits";

$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

if ($conexion->connect_error) {
    die("Error al conectar con la base de datos.");
}

$conexion->set_charset("utf8mb4");

$email = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'] ?? 'Usuario';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['eliminar_pregunta'])
) {

    $pregunta_id = (int)($_POST['pregunta_id'] ?? 0);

    if ($pregunta_id > 0) {

        $sql_eliminar = "
            DELETE FROM preguntas_contacto
            WHERE id = ? AND email = ?
        ";

        $stmt_eliminar = $conexion->prepare($sql_eliminar);

        $stmt_eliminar->bind_param(
            "is",
            $pregunta_id,
            $email
        );

        $stmt_eliminar->execute();
        $stmt_eliminar->close();
    }

    header("Location: perfil.php");
    exit();
}

$sql_quiz = "
    SELECT puntos, total, fecha
    FROM resultados_quiz
    WHERE usuario_email = ?
    ORDER BY fecha DESC
";

$stmt_quiz = $conexion->prepare($sql_quiz);
$stmt_quiz->bind_param("s", $email);
$stmt_quiz->execute();

$resultado_quiz = $stmt_quiz->get_result();

$sql_max = "
    SELECT MAX(puntos) AS mejor_puntaje
    FROM resultados_quiz
    WHERE usuario_email = ?
";

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

$sql_faq = "
    SELECT id, mensaje, fecha
    FROM preguntas_contacto
    WHERE email = ?
    ORDER BY fecha DESC
";

$stmt_faq = $conexion->prepare($sql_faq);
$stmt_faq->bind_param("s", $email);
$stmt_faq->execute();

$resultado_faq = $stmt_faq->get_result();

$sql_planes = "
    SELECT COUNT(*) AS total_materias
    FROM planes_estudio
    WHERE usuario_email = ?
";

$stmt_planes = $conexion->prepare($sql_planes);
$stmt_planes->bind_param("s", $email);
$stmt_planes->execute();

$datos_planes = $stmt_planes->get_result()->fetch_assoc();
$total_materias = (int)$datos_planes['total_materias'];

$stmt_planes->close();

$sql_tareas = "
    SELECT
        COUNT(*) AS total_tareas,
        COALESCE(SUM(t.completada), 0) AS tareas_hechas
    FROM tareas_estudio t
    INNER JOIN planes_estudio p
        ON t.plan_id = p.id
    WHERE p.usuario_email = ?
";

$stmt_tareas = $conexion->prepare($sql_tareas);
$stmt_tareas->bind_param("s", $email);
$stmt_tareas->execute();

$datos_tareas = $stmt_tareas->get_result()->fetch_assoc();

$total_tareas = (int)$datos_tareas['total_tareas'];
$tareas_hechas = (int)$datos_tareas['tareas_hechas'];

$stmt_tareas->close();

$porcentaje_estudio = $total_tareas > 0
    ? round(($tareas_hechas / $total_tareas) * 100)
    : 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Perfil de <?php echo htmlspecialchars($nombre); ?> | NeuroHabits
    </title>

    <link rel="stylesheet" href="css/estilos.css">

    <style>

        .perfil-contenedor {
            padding: 40px 20px;
            max-width: 850px;
            margin: auto;
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }

        .perfil-encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .perfil-encabezado h2 {
            color: #003662;
            margin: 0;
            font-size: 1.8rem;
        }

        .nivel {
            display: inline-block;
            margin-top: 6px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            background: #0f1c2e;
        }

        .cerrar-sesion {
            background: #e74c3c;
            color: white;
            padding: 9px 16px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .estadisticas {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 35px;
        }

        .estadistica {
            background: #0f1c2e;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
        }

        .estadistica span {
            font-size: 0.85rem;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
        }

        .estadistica p {
            margin: 8px 0 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .titulo-seccion {
            color: #002b49;
            margin-top: 40px;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 10px;
        }

        .tabla-quiz {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            color: white;
            background: #0f1c2e;
            border-radius: 8px;
            overflow: hidden;
        }

        .tabla-quiz th {
            background: #172a45;
            padding: 14px 16px;
            text-align: left;
        }

        .tabla-quiz td {
            padding: 14px 16px;
            border-bottom: 1px solid #233c63;
        }

        .preguntas-lista {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .pregunta-tarjeta {
            background: #0f1c2e;
            padding: 18px 20px;
            border-radius: 8px;
            border-left: 5px solid #00bcd4;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
        }

        .pregunta-contenido {
            flex: 1;
            min-width: 0;
        }

        .pregunta-contenido p {
            color: white;
            margin: 0 0 8px;
            font-size: 1.05rem;
            overflow-wrap: anywhere;
        }

        .pregunta-fecha {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .boton-eliminar {
            background: #23364d;
            border: 1px solid #3b5067;
            border-radius: 8px;
            padding: 9px;
            cursor: pointer;
            font-size: 16px;
        }

        .boton-eliminar:hover {
            background: #5c2730;
        }

        .calendario-perfil {
            background: #0f1c2e;
            border-radius: 12px;
            padding: 25px;
            margin-top: 18px;
            color: white;
        }

        .calendario-resumen {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 22px;
        }

        .calendario-dato {
            text-align: center;
            padding: 15px;
            background: #172a45;
            border-radius: 10px;
        }

        .calendario-dato strong {
            display: block;
            color: #64b5f6;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .calendario-dato span {
            color: #b8c7d8;
            font-size: 12px;
        }

        .barra-fondo {
            width: 100%;
            height: 10px;
            background: #30445e;
            border-radius: 20px;
            overflow: hidden;
            margin: 15px 0;
        }

        .barra-progreso {
            height: 100%;
            background: #64b5f6;
            border-radius: 20px;
        }

        .boton-calendario {
            display: inline-block;
            background: #64b5f6;
            color: #003662;
            padding: 12px 20px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }

        .boton-calendario:hover {
            background: #a5c9ee;
        }

        @media (max-width: 600px) {

            .perfil-contenedor {
                padding: 25px 15px;
            }

            .calendario-resumen {
                grid-template-columns: 1fr;
            }

            .pregunta-tarjeta {
                padding: 15px;
            }

        }

    </style>

</head>

<body style="
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    margin: 0;
">

<header class="header-principal">

    <div class="header-top">
        <img
            src="imagenes/logoneuro.webp"
            alt="Logo NeuroHabits"
        >
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
            <li><a href="habitos.php">Mis hábitos</a></li>

            <li>
                <a
                    href="perfil.php"
                    class="active"
                    style="color: #64b5f6; font-weight: bold;"
                >
                    Hola, <?php echo htmlspecialchars($nombre); ?>
                </a>
            </li>

            <li>
                <a href="logout.php" style="color: #ff7675;">
                    Salir
                </a>
            </li>

        </ul>

    </nav>

</header>

<main class="perfil-contenedor">

    <div class="perfil-encabezado">

        <div>

            <h2>
                Perfil de <?php echo htmlspecialchars($nombre); ?>
            </h2>

            <span
                class="nivel"
                style="color: <?php echo $color_nivel; ?>;"
            >
                <?php echo $nivel; ?>
            </span>

        </div>

        <a href="logout.php" class="cerrar-sesion">
            Cerrar sesión
        </a>

    </div>

    <div class="estadisticas">

        <div
            class="estadistica"
            style="border-bottom: 4px solid #00bcd4;"
        >

            <span>Intentos Quiz</span>

            <p>
                <?php echo $resultado_quiz->num_rows; ?>
            </p>

        </div>

        <div
            class="estadistica"
            style="border-bottom: 4px solid #00e676;"
        >

            <span>Mejor Puntaje</span>

            <p style="color: #00e676;">
                <?php
                echo $mejor_puntaje !== null
                    ? $mejor_puntaje . " / 8"
                    : "-";
                ?>
            </p>

        </div>

        <div
            class="estadistica"
            style="border-bottom: 4px solid #f59e0b;"
        >

            <span>Dudas Enviadas</span>

            <p>
                <?php echo $resultado_faq->num_rows; ?>
            </p>

        </div>

    </div>

    <h3 class="titulo-seccion">
        📅 Mi calendario de estudio
    </h3>

    <div class="calendario-perfil">

        <div class="calendario-resumen">

            <div class="calendario-dato">

                <strong>
                    <?php echo $total_materias; ?>
                </strong>

                <span>Materias</span>

            </div>

            <div class="calendario-dato">

                <strong>
                    <?php echo $tareas_hechas; ?>
                </strong>

                <span>Tareas completadas</span>

            </div>

            <div class="calendario-dato">

                <strong>
                    <?php echo $porcentaje_estudio; ?>%
                </strong>

                <span>Progreso general</span>

            </div>

        </div>

        <div class="barra-fondo">

            <div
                class="barra-progreso"
                style="width: <?php echo $porcentaje_estudio; ?>%;"
            ></div>

        </div>

        <a href="habitos.php" class="boton-calendario">
            Ver mi calendario
        </a>

    </div>

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 15px;
        border-bottom: 2px solid #cbd5e1;
        margin-top: 45px;
        padding-bottom: 8px;
    ">

        <h3 style="color: #002b49; margin: 0;">
            🧠 Intentos en el Quiz Cognitivo
        </h3>

        <?php if ($resultado_quiz->num_rows > 0): ?>

            <a
                href="limpiar_historial.php"
                onclick="return confirm('¿Seguro que querés reiniciar tu historial del quiz?');"
                style="
                    color: #ef4444;
                    font-size: 0.8rem;
                    text-decoration: underline;
                    font-weight: 600;
                "
            >
                Restablecer historial
            </a>

        <?php endif; ?>

    </div>

    <?php if ($resultado_quiz->num_rows > 0): ?>

        <table class="tabla-quiz">

            <thead>

                <tr>
                    <th>Intento</th>
                    <th>Puntaje</th>
                    <th>Fecha y Hora</th>
                </tr>

            </thead>

            <tbody>

                <?php
                $intento = $resultado_quiz->num_rows;

                while ($fila = $resultado_quiz->fetch_assoc()):
                ?>

                    <tr>

                        <td>
                            Intento #<?php echo $intento--; ?>
                        </td>

                        <td style="
                            font-weight: bold;
                            color: #00e676;
                        ">
                            <?php echo $fila['puntos']; ?>
                            /
                            <?php echo $fila['total']; ?>
                        </td>

                        <td style="color: #94a3b8;">
                            <?php echo $fila['fecha']; ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p style="margin-top: 15px; color: #475569;">

            Todavía no completaste el Quiz.

            <a
                href="quiz.php"
                style="color: #0077b6; font-weight: bold;"
            >
                ¡Hacé tu primer intento acá!
            </a>

        </p>

    <?php endif; ?>

    <h3 class="titulo-seccion">
        📩 Preguntas que enviaste al equipo
    </h3>

    <?php if ($resultado_faq->num_rows > 0): ?>

        <div class="preguntas-lista">

            <?php while ($pregunta = $resultado_faq->fetch_assoc()): ?>

                <div class="pregunta-tarjeta">

                    <div class="pregunta-contenido">

                        <p>
                            "<?php echo htmlspecialchars($pregunta['mensaje']); ?>"
                        </p>

                        <span class="pregunta-fecha">
                            Enviada el:
                            <?php echo htmlspecialchars($pregunta['fecha']); ?>
                        </span>

                    </div>

                    <form
                        method="POST"
                        onsubmit="return confirm('¿Estás segura de que querés eliminar esta pregunta?');"
                        style="margin: 0;"
                    >

                        <input
                            type="hidden"
                            name="pregunta_id"
                            value="<?php echo (int)$pregunta['id']; ?>"
                        >

                        <button
                            type="submit"
                            name="eliminar_pregunta"
                            value="1"
                            class="boton-eliminar"
                            title="Eliminar pregunta"
                            aria-label="Eliminar pregunta"
                        >
                            🗑️
                        </button>

                    </form>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <p style="margin-top: 15px; color: #475569;">

            No realizaste ninguna pregunta todavía.
            Si tenés dudas,

            <a
                href="preguntas.php"
                style="color: #0077b6; font-weight: bold;"
            >
                dejá tu consulta acá
            </a>.

        </p>

    <?php endif; ?>

</main>

<footer
    class="footer-principal"
    style="width: 100%; margin-top: auto;"
>

    <div class="contenedor-footer">

        <div class="contacto-footer">

            <div class="linea-contacto">

                <img
                    src="imagenes/insta.png"
                    alt="Instagram"
                    class="icono-footer"
                >

                <span>@NeuroHabits</span>

            </div>

            <div class="linea-contacto">

                <img
                    src="imagenes/mail.png"
                    alt="Email"
                    class="icono-footer"
                >

                <span>neurohabits@gmail.com</span>

            </div>

            <div class="linea-contacto">

                <img
                    src="imagenes/tele.png"
                    alt="Teléfono"
                    class="icono-footer"
                >

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
