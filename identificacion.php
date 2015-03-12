<?php

include_once './lib.php';

View::start('Login');
intentosPrevios();
formularioRegistro();
View::end();

function formularioRegistro() {
    echo
    '<form action = "validacion.php" method = "post">
    <p>Username: <input type = "text" name = "username" /></p>
    <p>Password: <input type = "password" name = "password" /></p>
    <p><input type = "submit" value="Entrar" /></p>
    </form>';

    echo '<p>¿Aún no tienes cuenta? ¡Pues regístrate!</p>';
    echo '<a href="registro.php">Registro</a>';
}

function intentosPrevios() {
    switch (filter_input(INPUT_GET, 'error')) {
        case 1:
            echo '<p>Asegúrese de haber rellenado ambos campos...</p>';
            break;
        case 2:
            echo
            '<p>No se puede determinar que las credenciales proporcionadas sean auténticas. Por favor,'
            . ' vuelva a intentarlo...</p>';
            break;
        default:
            break;
    }
}
