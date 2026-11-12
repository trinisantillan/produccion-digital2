# NeuroHabits 🧠

## Descripción del proyecto

NeuroHabits es un sitio web educativo e interactivo que busca ayudar a los estudiantes a comprender cómo funciona el cerebro y mejorar sus hábitos de estudio mediante herramientas basadas en la neurociencia y las ciencias cognitivas.

El sitio fue desarrollado utilizando HTML, CSS, JavaScript, PHP y MySQL, implementando una arquitectura cliente-servidor que permite almacenar, consultar y modificar información.

## Navegación del sitio

El sitio cuenta con distintas secciones:

- **Inicio:** presentación general de NeuroHabits.
- **¿Qué es?:** introducción a la neurociencia.
- **Ciencias Cognitivas:** información sobre los procesos cognitivos.
- **Estudio y memoria:** técnicas y herramientas para mejorar el aprendizaje.
- **FAQ:** preguntas frecuentes y formulario de consultas.
- **Quiz:** cuestionario interactivo para evaluar conocimientos.
- **Mi calendario:** planificación de materias, exámenes y tareas.
- **Mi perfil:** espacio personal del usuario.

## Registro e inicio de sesión

Los usuarios pueden registrarse mediante un formulario que almacena sus datos en MySQL.

Posteriormente, pueden iniciar sesión utilizando sus credenciales.

El sistema utiliza sesiones de PHP para identificar a cada usuario y permitir el acceso a las secciones personalizadas.

## Formularios y base de datos

NeuroHabits utiliza PHP para procesar los formularios y comunicarse con una base de datos MySQL.

La información almacenada incluye los datos de los usuarios, las consultas realizadas, los resultados del Quiz y los planes de estudio.

## Mi perfil

Cada usuario dispone de un perfil personal donde puede consultar su actividad dentro del sitio, visualizar sus resultados del Quiz y acceder a sus preguntas y al progreso de su calendario de estudio.

## Calendario de estudio

Los usuarios pueden crear calendarios personalizados ingresando una materia y la fecha de su examen.

El sistema genera una planificación de siete días, en los que se pueden agregar tareas, marcarlas como completadas y guardar los cambios en la base de datos.

También permite consultar el porcentaje de progreso y eliminar materias.

## Tecnologías utilizadas

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP
- phpMyAdmin

## Ejecución del proyecto

Para ejecutar NeuroHabits localmente:

1. Abrir XAMPP e iniciar Apache y MySQL.
2. Colocar la carpeta del proyecto en `htdocs`.
3. Ingresar a phpMyAdmin e importar la base de datos `neurohabits` desde el archivo SQL.
4. Abrir el navegador.
5. Ingresar a `http://localhost/produccion-digital2/`.

De esta manera, el sitio puede ejecutarse en un servidor local y utilizar sus funcionalidades dinámicas.