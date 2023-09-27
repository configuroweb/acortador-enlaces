<?php
// Iniciar una sesión PHP para mantener datos entre páginas.
session_start();

// Incluir el archivo de configuración del sitio.
require_once("./config.php");
?>

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Acortador de URL">
    <title><?php echo SITE_NAME; ?></title>
    <!-- Incluir la hoja de estilos CSS para la página. -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <br>
    <center>
        <!-- Mostrar el nombre del sitio como título principal. -->
        <h1><?php echo SITE_NAME; ?></h1>
        <h4><a href="https://www.configuroweb.com/" target="_blank" style="text-decoration : none; color:white">Para más desarrollos ConfiguroWeb</a></h4><br><br><br>
        <?php
        // Comprobar si existe un mensaje de éxito en la sesión y mostrarlo.
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>" . $_SESSION['success'] . "</p>";
            // Eliminar el mensaje de éxito de la sesión para no mostrarlo nuevamente.
            unset($_SESSION['success']);
        }
        // Comprobar si existe un mensaje de error en la sesión y mostrarlo.
        if (isset($_SESSION['error'])) {
            echo "<p class='alert'>" . $_SESSION['error'] . "</p>";
            // Eliminar el mensaje de error de la sesión para no mostrarlo nuevamente.
            unset($_SESSION['error']);
        }
        // Comprobar si hay un error específico en la URL y mostrar mensajes correspondientes.
        if (isset($_GET['error']) && $_GET['error'] == 'db') {
            echo "<p class='alert'>¡Error al conectar a la base de datos!</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'inurl') {
            echo "<p class='alert'>¡URL no válida!</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'dnp') {
            echo "<p class='alert'>¡Bien! ¡Entendí que te gusta jugar, pero no juegues aquí!</p>";
        }
        ?>
        <!-- Formulario para acortar URL. -->
        <form method="POST" action="functions/shorten.php">
            <div class="section group">
                <div class="col span_3_of_3">
                    <!-- Campo de entrada para la URL original. -->
                    <input type="url" id="input" name="url" class="input" placeholder="Ingresa tu URL aquí">
                </div>
                <div class="col span_1_of_3">
                    <!-- Campo de entrada para un texto personalizado (desactivado por defecto). -->
                    <input type="text" id="custom" name="custom" class="input_custom" placeholder="Puedes poner un texto personalizado sin espacios" disabled>
                </div>
                <div class="col span_2_of_3">
                    <!-- Interruptor para habilitar/deshabilitar el campo de texto personalizado. -->
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" onclick="toggle()">
                        <label class="onoffswitch-label" for="myonoffswitch"></label>
                    </div>
                </div>
            </div>
            <!-- Botón para enviar el formulario. -->
            <input type="submit" value="Enviar" class="submit">
        </form>
        <script>
            // Función para alternar la habilitación del campo de texto personalizado.
            function toggle() {
                if (document.getElementById('myonoffswitch').checked) {
                    // Habilitar el campo y cambiar el marcador de posición.
                    document.getElementById('custom').placeholder = 'Ingresa tu texto personalizado sin espacios'
                    document.getElementById('custom').disabled = false
                    document.getElementById('custom').focus()
                } else {
                    // Deshabilitar el campo y borrar su contenido.
                    document.getElementById('custom').value = ''
                    document.getElementById('custom').placeholder = 'Ingresa tu texto personalizado sin espacios'
                    document.getElementById('custom').disabled = true
                    document.getElementById('custom').blur()
                    // Dar foco al campo de entrada de URL.
                    document.getElementById('input').focus()
                }
            }
        </script>
</body>

</html>