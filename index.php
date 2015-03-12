<?php

include_once 'lib.php';

View::start('Conversor de moneda');
echo '<h1>CONVERSOR DE MONEDA GRATUITO</h1>';
echo "<a href='identificacion.php'>Acceso</a>";
intentosPrevios();
echo formularioConversor();
mostrarResultado();
View::end();

function formularioConversor() {
    $bd = new PDO('sqlite:./ftsi.db');
    $opciones = '';
    $divisas = $bd->query('SELECT DIVISA FROM DIVISAS');
    foreach ($divisas as $value) {
        $opciones = $opciones . "<option value=$value[divisa]>$value[divisa]</option>";
    }
    return "
    <form action = 'cambio.php' method = 'post'>
        <fieldset>
        <legend>Conversor</legend>
        <select name = 'from' size = 15 multiple = 'multiple'>
        <optgroup label = 'From'> . $opciones .
        </optgroup>
        </select>
        <select name = 'to' size = 15 multiple = 'multiple'>
        <optgroup label = 'To'> . $opciones .
        </optgroup>
        </select>
        <p>Introduzca una cantidad: <input type='text' name='cantidad' /></p>
        <input type='submit' value='Realizar Cambio' />
        </fieldset>
   </form> ";
}

function intentosPrevios() {
    switch (filter_input(INPUT_GET, 'cambio')) {
        case '1':
            echo '<p>Por favor, asegúrese de haber seleccionado un origen y un destino para realziar el cambio, además de una cantidad</p>';
            break;
        case '3':
            echo '<p>Por favor, únicamente introduzca números</p>';
            break;
        default:
            break;
    }
}

function mostrarResultado() {
    session_start();
    if (filter_input(INPUT_GET, 'cambio') == '2') {
        echo '<p>' . $_SESSION['cantidad'] . " " . $_SESSION['from'] . ' son ' . $corta = substr($_SESSION['resultado'], 0, strpos($_SESSION['resultado'], '.') + 3) . ' ' . $_SESSION['to'] . '</p>';
    }
    $desde = (time() - $_SESSION['fecha']);
    echo '<p>Valor de la moneda tomado última vez hace ' . $desde . ' segundos</p>';
    session_write_close();
}
