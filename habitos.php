
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = new mysqli('localhost', 'root', '', 'neurohabits');
$conexion->set_charset('utf8mb4');

$email = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'] ?? 'Usuario';
$error = '';

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function fecha_valida($v) {
    if (!is_string($v)) return false;

    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $v);

    return $d && $d->format('Y-m-d') === $v;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_plan'])) {

    $id = (int)($_POST['plan_id'] ?? 0);

    if ($id > 0) {
        try {
            $conexion->begin_transaction();

            $s = $conexion->prepare(
                'SELECT id FROM planes_estudio
                 WHERE id = ? AND usuario_email = ?
                 FOR UPDATE'
            );

            $s->bind_param('is', $id, $email);
            $s->execute();

            $propio = $s->get_result()->num_rows > 0;
            $s->close();

            if ($propio) {

                $s = $conexion->prepare(
                    'DELETE FROM tareas_estudio WHERE plan_id = ?'
                );

                $s->bind_param('i', $id);
                $s->execute();
                $s->close();

                $s = $conexion->prepare(
                    'DELETE FROM planes_estudio
                     WHERE id = ? AND usuario_email = ?'
                );

                $s->bind_param('is', $id, $email);
                $s->execute();
                $s->close();
            }

            $conexion->commit();

            header('Location: habitos.php');
            exit();

        } catch (Throwable $e) {
            $conexion->rollback();
            $error = 'No se pudo eliminar la materia.';
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_plan'])) {

    $materia = trim($_POST['materia'] ?? '');
    $fecha_examen = $_POST['fecha_examen'] ?? '';

    if (
        $materia === '' ||
        mb_strlen($materia) > 150 ||
        !fecha_valida($fecha_examen)
    ) {
        $error = 'Completá correctamente la materia y la fecha.';

    } elseif ($fecha_examen <= date('Y-m-d')) {

        $error = 'El examen tiene que ser posterior a hoy.';

    } else {

        $s = $conexion->prepare(
            'INSERT INTO planes_estudio
             (usuario_email, materia, fecha_examen)
             VALUES (?, ?, ?)'
        );

        $s->bind_param('sss', $email, $materia, $fecha_examen);
        $s->execute();

        $id = $conexion->insert_id;
        $s->close();

        header('Location: habitos.php?plan=' . $id);
        exit();
    }
}


$planes = [];

$s = $conexion->prepare(
    'SELECT id, materia, fecha_examen
     FROM planes_estudio
     WHERE usuario_email = ?
     ORDER BY fecha_examen ASC'
);

$s->bind_param('s', $email);
$s->execute();

$r = $s->get_result();

while ($f = $r->fetch_assoc()) {
    $planes[] = $f;
}

$s->close();


$plan_actual = null;

$id_solicitado = (int)($_GET['plan'] ?? $_POST['plan_id'] ?? 0);

foreach ($planes as $p) {
    if ((int)$p['id'] === $id_solicitado) {
        $plan_actual = $p;
        break;
    }
}

if ($plan_actual === null && $planes) {
    $plan_actual = $planes[0];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_dia'])) {

    $plan_id = (int)($_POST['plan_id'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';

    $plan_guardado = null;

    foreach ($planes as $p) {
        if ((int)$p['id'] === $plan_id) {
            $plan_guardado = $p;
            break;
        }
    }

    if ($plan_guardado && fecha_valida($fecha)) {

        $inicio = (
            new DateTimeImmutable($plan_guardado['fecha_examen'])
        )->modify('-7 days')->format('Y-m-d');

        if (
            $fecha >= $inicio &&
            $fecha < $plan_guardado['fecha_examen']
        ) {

            for ($i = 1; $i <= 2; $i++) {

                $tarea = mb_substr(
                    trim($_POST['tarea' . $i] ?? ''),
                    0,
                    255
                );

                $hecha = isset($_POST['hecha' . $i]) ? 1 : 0;

                if ($tarea === '') {

                    $s = $conexion->prepare(
                        'DELETE FROM tareas_estudio
                         WHERE plan_id = ?
                         AND fecha = ?
                         AND numero = ?'
                    );

                    $s->bind_param('isi', $plan_id, $fecha, $i);

                } else {

                    $s = $conexion->prepare(
                        'INSERT INTO tareas_estudio
                         (plan_id, fecha, numero, tarea, completada)
                         VALUES (?, ?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE
                         tarea = VALUES(tarea),
                         completada = VALUES(completada)'
                    );

                    $s->bind_param(
                        'isisi',
                        $plan_id,
                        $fecha,
                        $i,
                        $tarea,
                        $hecha
                    );
                }

                $s->execute();
                $s->close();
            }

            header(
                'Location: habitos.php?plan=' .
                $plan_id .
                '&dia=' . $fecha .
                '#semana'
            );

            exit();
        }
    }

    $error = 'No se pudieron guardar las tareas de ese día.';
}


$tareas = [];
$dias = [];

$total = 0;
$hechas = 0;
$restantes = 0;

if ($plan_actual) {

    $plan_id = (int)$plan_actual['id'];

    $s = $conexion->prepare(
        'SELECT fecha, numero, tarea, completada
         FROM tareas_estudio
         WHERE plan_id = ?'
    );

    $s->bind_param('i', $plan_id);
    $s->execute();

    $r = $s->get_result();

    while ($f = $r->fetch_assoc()) {

        $tareas[$f['fecha']][(int)$f['numero']] = $f;

        if (trim($f['tarea']) !== '') {

            $total++;

            if ((int)$f['completada'] === 1) {
                $hechas++;
            }
        }
    }

    $s->close();

    $inicio = new DateTimeImmutable(
        $plan_actual['fecha_examen']
    );

    $inicio = $inicio->modify('-7 days');

    $nombres = [
        1 => 'Lun',
        2 => 'Mar',
        3 => 'Mié',
        4 => 'Jue',
        5 => 'Vie',
        6 => 'Sáb',
        7 => 'Dom'
    ];

    for ($i = 0; $i < 7; $i++) {

        $d = $inicio->modify('+' . $i . ' days');

        $dias[] = [
            'fecha' => $d->format('Y-m-d'),
            'nombre' => $nombres[(int)$d->format('N')],
            'numero' => $d->format('d')
        ];
    }

    $hoy = new DateTimeImmutable('today');

    $examen = new DateTimeImmutable(
        $plan_actual['fecha_examen']
    );

    if ($examen > $hoy) {
        $restantes = $hoy->diff($examen)->days;
    }
}

$porcentaje = $total
    ? round($hechas * 100 / $total)
    : 0;

$seleccionado = $_GET['dia'] ?? '';

$fechas = array_column($dias, 'fecha');

if (!in_array($seleccionado, $fechas, true)) {

    $seleccionado = in_array(date('Y-m-d'), $fechas, true)
        ? date('Y-m-d')
        : ($fechas[0] ?? '');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mi calendario | NeuroHabits</title>

<link rel="stylesheet" href="css/estilos.css">

<style>

body {
    background: #f1f5fa;
}

.plan-container {
    max-width: 1100px;
    margin: auto;
    padding: 42px 22px 70px;
}

.plan-encabezado {
    margin-bottom: 28px;
}

.plan-encabezado small {
    color: #507da2;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
}

.plan-encabezado h1 {
    color: #003662;
    font-size: 36px;
    margin: 10px 0;
}

.plan-encabezado p,
.descripcion {
    color: #617a91;
    font-size: 14px;
    line-height: 1.6;
}

.panel {
    background: #fff;
    border-radius: 18px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 5px 24px rgba(0,54,98,.06);
}

.panel h2 {
    color: #003662;
    font-size: 21px;
    margin: 0 0 12px;
}

.formulario-plan {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 14px;
    align-items: end;
    margin-top: 20px;
}

.campo {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.campo label {
    font-size: 13px;
    font-weight: 700;
    color: #003662;
}

.campo input,
.tarea-input {
    box-sizing: border-box;
    width: 100%;
    padding: 12px;
    border: 1px solid #d5e1ed;
    border-radius: 9px;
    background: #f9fbff;
    color: #003662;
    font-size: 14px;
}

.boton {
    border: 0;
    background: #003662;
    color: #fff;
    border-radius: 9px;
    padding: 13px 19px;
    font-weight: 700;
    cursor: pointer;
}

.boton:hover {
    background: #075184;
}

.error {
    padding: 12px;
    background: #fff0f0;
    color: #a12e35;
    border-radius: 8px;
    margin: 12px 0;
}

.materias {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-top: 18px;
}

.materia {
    border: 1px solid #dbe6f0;
    border-radius: 12px;
    background: #f7faff;
    position: relative;
}

.materia.activa {
    background: #e9f3ff;
    border-color: #003662;
}

.materia a {
    display: flex;
    flex-direction: column;
    gap: 7px;
    padding: 17px 48px 17px 16px;
    color: #003662;
    text-decoration: none;
    min-height: 68px;
}

.materia strong {
    font-size: 15px;
    overflow-wrap: anywhere;
}

.materia small {
    font-size: 12px;
    color: #6c8399;
}

.materia form {
    position: absolute;
    right: 10px;
    top: 10px;
}

.borrar {
    border: 1px solid #e0e6ee;
    background: white;
    border-radius: 8px;
    padding: 7px;
    cursor: pointer;
}

.borrar:hover {
    background: #ffeded;
}

.resumen {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
}

.resumen h2 {
    margin: 0 0 6px;
}

.resumen p {
    margin: 0;
    color: #667f94;
    font-size: 14px;
}

.cuenta {
    background: #e6f1ff;
    color: #003662;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
}

.progreso {
    display: flex;
    gap: 18px;
    align-items: center;
    border-top: 1px solid #e6edf4;
    margin-top: 22px;
    padding-top: 20px;
}

.progreso-numero {
    font-size: 36px;
    color: #003662;
    font-weight: 800;
}

.progreso-resto {
    flex: 1;
    color: #667f94;
    font-size: 13px;
}

.barra-fondo {
    height: 9px;
    background: #e5edf6;
    border-radius: 20px;
    overflow: hidden;
    margin-top: 9px;
}

.barra {
    height: 100%;
    background: #64b5f6;
    border-radius: 20px;
}

.dias {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 9px;
    margin: 20px 0;
}

.dia {
    border: 1px solid #dbe6f0;
    border-radius: 12px;
    background: #f7faff;
    color: #003662;
    padding: 14px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    min-width: 0;
}

.dia span {
    font-size: 11px;
    font-weight: 700;
}

.dia strong {
    font-size: 23px;
}

.dia small {
    font-size: 11px;
    color: #66829c;
}

.dia.seleccionado {
    background: #003662;
    color: white;
    border-color: #003662;
}

.dia.seleccionado small {
    color: #d3e8fa;
}

.dia.hoy:not(.seleccionado) {
    border-color: #64b5f6;
}


.detalle-dia {
    border: 1px solid #e0e9f2;
    background: #f7faff;
    border-radius: 14px;
    padding: 22px;
}
.detalle-dia[hidden] {
    display: none;
}

.detalle-dia h3 {
    color: #003662;
    margin: 0 0 7px;
    font-size: 20px;
}

.detalle-dia p {
    color: #698197;
    font-size: 13px;
    margin: 0 0 18px;
}

.tareas-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 18px;
}

.tarea-bloque {
    background: white;
    border: 1px solid #e1eaf3;
    border-radius: 10px;
    padding: 15px;
}

.tarea-bloque > label {
    display: block;
    color: #527592;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 9px;
}

.checkline {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    color: #003662;
    font-size: 13px;
}

.checkline input {
    width: 17px;
    height: 17px;
    accent-color: #003662;
}

.ayuda {
    color: #70869b;
    font-size: 12px;
    margin-top: 12px;
}

.sin-plan {
    text-align: center;
    color: #718399;
    padding: 32px;
}

.sin-plan h2 {
    color: #003662;
}


@media (max-width: 750px) {

    .plan-container {
        padding: 28px 14px 45px;
    }

    .plan-encabezado h1 {
        font-size: 29px;
    }

    .panel {
        padding: 18px;
    }

    .formulario-plan,
    .tareas-grid {
        grid-template-columns: 1fr;
    }

    .dias {
        gap: 4px;
    }

    .dia {
        padding: 11px 2px;
        gap: 5px;
        border-radius: 8px;
    }

    .dia span {
        font-size: 9px;
    }

    .dia strong {
        font-size: 17px;
    }

    .dia small {
        font-size: 9px;
    }

    .detalle-dia {
        padding: 15px;
    }
}

</style>

</head>

<body>

<header class="header-principal">

    <div class="header-top">
        <h1>🧠 NeuroHabits</h1>
    </div>

    <nav class="nav-principal">

        <input
            type="checkbox"
            id="menu-toggle"
            class="menu-checkbox"
        >

        <label for="menu-toggle" class="hamburger-btn">
            <span></span>
            <span></span>
            <span></span>
        </label>

        <ul class="nav-menu">

            <li><a href="index.php">Inicio</a></li>
            <li><a href="que.php">¿Qué es?</a></li>
            <li><a href="cc.php">Ciencias Cognitivas</a></li>
            <li><a href="estudio.php">Estudio y memoria</a></li>
            <li><a href="preguntas.php">FAQ</a></li>
            <li><a href="quiz.php">Quiz</a></li>

            <li>
                <a href="habitos.php" class="active">
                    Mi calendario
                </a>
            </li>

            <li>
                <a href="perfil.php">
                    Hola, <?= h($nombre) ?>
                </a>
            </li>

            <li><a href="logout.php">Salir</a></li>

        </ul>

    </nav>

</header>

<main class="plan-container">

    <div class="plan-encabezado">

        <small>
            NEUROHABITS · ORGANIZÁ TU ESTUDIO
        </small>

        <h1>Mi calendario de estudio</h1>

        <p>
            Un plan más claro para preparar tus próximos exámenes.
        </p>

    </div>

    <!-- CREAR CALENDARIO -->

    <section class="panel">

        <h2>Crear un nuevo calendario</h2>

        <p class="descripcion">
            Agregá una materia y elegí cuándo rendís.
        </p>

        <?php if ($error !== ''): ?>

            <div class="error">
                <?= h($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="formulario-plan">

            <div class="campo">

                <label for="materia">
                    Materia
                </label>

                <input
                    id="materia"
                    name="materia"
                    maxlength="150"
                    placeholder="Ej. Comunicación Digital I"
                    required
                >

            </div>

            <div class="campo">

                <label for="fecha_examen">
                    Fecha del examen
                </label>

                <input
                    type="date"
                    id="fecha_examen"
                    name="fecha_examen"
                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                    required
                >

            </div>

            <button
                class="boton"
                name="crear_plan"
                value="1"
            >
                + Crear calendario
            </button>

        </form>

    </section>

    <?php if ($planes): ?>

        <section class="panel">

            <h2>Mis materias</h2>

            <p class="descripcion">
                Elegí la materia que querés organizar.
            </p>

            <div class="materias">

                <?php foreach ($planes as $p): ?>

                    <div class="materia
                        <?= (int)$p['id'] === (int)$plan_actual['id']
                            ? 'activa' : '' ?>">

                        <a href="habitos.php?plan=<?= (int)$p['id'] ?>">

                            <strong>
                                <?= h($p['materia']) ?>
                            </strong>

                            <small>
                                Examen:
                                <?= date(
                                    'd/m/Y',
                                    strtotime($p['fecha_examen'])
                                ) ?>
                            </small>

                        </a>

                        <form
                            method="POST"
                            onsubmit="return confirm('¿Eliminar esta materia y todas sus tareas? Esta acción no se puede deshacer.');"
                        >

                            <input
                                type="hidden"
                                name="plan_id"
                                value="<?= (int)$p['id'] ?>"
                            >

                            <button
                                type="submit"
                                name="eliminar_plan"
                                value="1"
                                class="borrar"
                                aria-label="Eliminar <?= h($p['materia']) ?>"
                                title="Eliminar materia"
                            >
                                🗑️
                            </button>

                        </form>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>

    <!-- RESUMEN DE LA MATERIA -->

    <?php if ($plan_actual): ?>

        <section class="panel">

            <div class="resumen">

                <div>

                    <h2>
                        <?= h($plan_actual['materia']) ?>
                    </h2>

                    <p>
                        Examen:
                        <?= date(
                            'd/m/Y',
                            strtotime($plan_actual['fecha_examen'])
                        ) ?>
                    </p>

                </div>

                <div class="cuenta">

                    <?php if ($restantes === 0): ?>

                        ¡Hoy rendís!

                    <?php else: ?>

                        Faltan <?= $restantes ?>
                        <?= $restantes === 1 ? 'día' : 'días' ?>

                    <?php endif; ?>

                </div>

            </div>

            <div class="progreso">

                <div class="progreso-numero">
                    <?= $porcentaje ?>%
                </div>

                <div class="progreso-resto">

                    <?= $hechas ?> de <?= $total ?>
                    tareas completadas

                    <div class="barra-fondo">

                        <div
                            class="barra"
                            style="width: <?= $porcentaje ?>%;"
                        ></div>

                    </div>

                </div>

            </div>

        </section>

        <!-- CALENDARIO SEMANAL -->

        <section class="panel" id="semana">

            <h2>Tu semana de estudio</h2>

            <p class="descripcion">
                Elegí un día para ver y organizar solamente sus tareas.
            </p>

            <div class="dias">

                <?php foreach ($dias as $d): ?>

                    <?php
                    $f = $d['fecha'];

                    $lista = $tareas[$f] ?? [];

                    $completas = 0;

                    foreach ($lista as $t) {
                        if (
                            trim($t['tarea']) !== '' &&
                            (int)$t['completada'] === 1
                        ) {
                            $completas++;
                        }
                    }
                    ?>

                    <button
                        type="button"
                        class="dia
                            <?= $f === $seleccionado ? 'seleccionado' : '' ?>
                            <?= $f === date('Y-m-d') ? 'hoy' : '' ?>"
                        data-fecha="<?= h($f) ?>"
                        aria-pressed="<?= $f === $seleccionado ? 'true' : 'false' ?>"
                    >

                        <span>
                            <?= h($d['nombre']) ?>
                        </span>

                        <strong>
                            <?= h($d['numero']) ?>
                        </strong>

                        <small>
                            <?= $completas === 2
                                ? '✓ 2/2'
                                : $completas . '/2' ?>
                        </small>

                    </button>

                <?php endforeach; ?>

            </div>

            <!-- DETALLE DEL DÍA SELECCIONADO -->

            <?php foreach ($dias as $d): ?>

                <?php
                $f = $d['fecha'];

                $t1 = $tareas[$f][1] ?? null;
                $t2 = $tareas[$f][2] ?? null;
                ?>

                <div
                    class="detalle-dia"
                    data-panel-fecha="<?= h($f) ?>"
                    <?= $f === $seleccionado ? '' : 'hidden' ?>
                >

                    <h3>
                        <?= h($d['nombre']) ?>
                        <?= h($d['numero']) ?>
                    </h3>

                    <p>
                        Escribí tus tareas y marcá las que ya terminaste.
                    </p>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="guardar_dia"
                            value="1"
                        >

                        <input
                            type="hidden"
                            name="plan_id"
                            value="<?= (int)$plan_actual['id'] ?>"
                        >

                        <input
                            type="hidden"
                            name="fecha"
                            value="<?= h($f) ?>"
                        >

                        <div class="tareas-grid">

                            <!-- TAREA 1 Y TAREA 2 -->

                            <?php foreach ([1 => $t1, 2 => $t2] as $n => $t): ?>

                                <div class="tarea-bloque">

                                    <label for="tarea-<?= h($f) ?>-<?= $n ?>">
                                        TAREA <?= $n ?>
                                    </label>

                                    <input
                                        class="tarea-input"
                                        type="text"
                                        id="tarea-<?= h($f) ?>-<?= $n ?>"
                                        name="tarea<?= $n ?>"
                                        maxlength="255"
                                        placeholder="¿Qué vas a estudiar?"
                                        value="<?= h($t['tarea'] ?? '') ?>"
                                    >

                                    <label class="checkline">

                                        <input
                                            type="checkbox"
                                            name="hecha<?= $n ?>"
                                            value="1"
                                            <?= $t && (int)$t['completada'] === 1
                                                ? 'checked' : '' ?>
                                        >

                                        Completada

                                    </label>

                                </div>

                            <?php endforeach; ?>

                        </div>

                        <button type="submit" class="boton">
                            Guardar día
                        </button>

                    </form>

                    <div class="ayuda">
                        Los cambios se guardan al tocar “Guardar día”.
                    </div>

                </div>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <section class="panel sin-plan">

            <h2>¡Empecemos a organizarte! 🧠</h2>

            <p>
                Agregá una materia y la fecha de tu examen
                para crear tu calendario.
            </p>

        </section>

    <?php endif; ?>

</main>

<!-- FOOTER -->

<footer>

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

<!-- CAMBIAR ENTRE DÍAS SIN RECARGAR LA PÁGINA -->

<script>

const botones = document.querySelectorAll('[data-fecha]');

const paneles = document.querySelectorAll('[data-panel-fecha]');

botones.forEach(boton => {

    boton.addEventListener('click', () => {

        const fecha = boton.dataset.fecha;

        botones.forEach(b => {

            const activo = b.dataset.fecha === fecha;

            b.classList.toggle('seleccionado', activo);

            b.setAttribute(
                'aria-pressed',
                activo ? 'true' : 'false'
            );

        });

        paneles.forEach(p => {

            p.hidden = p.dataset.panelFecha !== fecha;

        });

    });

});

</script>

</body>
</html>

<?php
$conexion->close();
?>
