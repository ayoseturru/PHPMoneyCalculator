<?php

if (!filter_input(INPUT_POST, 'from') || !filter_input(INPUT_POST, 'to') || !filter_input(INPUT_POST, 'cantidad')) {
    header('Location: index.php?cambio=1');
    exit(1);
} else if (!ctype_digit(filter_input(INPUT_POST, 'cantidad'))) {
    header('Location: index.php?cambio=3');
    exit(3);
} else {
    $ultimaActualizacion = (new PDO('sqlite:./ftsi.db'))->query('SELECT FECHA FROM FECHA')->fetchColumn();
    if ((time() - $ultimaActualizacion) > 86400) {
        obtenerValores();
    } else {
        realizarCambio();
        header('Location: index.php?cambio=2');
        exit(0);
    }
}

function obtenerValores() {
    $url = 'http://api.fixer.io/latest';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $arr1 = preg_split("[:|,|}]", $data);

    if (count($arr1) < 50) {
        realizarCambio();
    } else {
        $arr1 = array_splice($arr1, 5);
        $arr1 = array_splice($arr1, 0, 62);
        $pos = 0;
        $lugar = 0;
        for ($i = 0; $i < 62; $i++) {
            if ($i % 2 == 0) {
                $nombres[$pos] = str_replace("\"", "", $arr1[$i]);
                $pos = $pos + 1;
            } else {
                $valor[$lugar] = $arr1[$i] + 0.0;
                $lugar = $lugar + 1;
            }
        }
        $nombres[0] = str_replace("{", "", $nombres[0]);
        actualizar($nombres, $valor);
    }
}

function actualizar($nombres, $valor) {
    $db = new PDO('sqlite:./ftsi.db');
    $db->query('UPDATE FECHA SET FECHA=' . time());
    ;
    for ($i = 0; $i < count($nombres); $i++) {
        $aux = $db->prepare('UPDATE VALORES SET valor=? where divisa=?');
        $aux->execute(array($valor[$i], $nombres[$i]));
    }
    $aux = $db->prepare('UPDATE VALORES SET valor=? where divisa=?');
    $aux->execute(array(1, "EUR"));
    realizarCambio();
}

function realizarCambio() {
    $bd = new PDO('sqlite:./ftsi.db');
    $from = (1 / ($bd->query('SELECT VALOR FROM VALORES WHERE DIVISA ="' . filter_input(INPUT_POST, 'from') . '"')->fetchColumn())) * filter_input(INPUT_POST, 'cantidad');
    $to = $bd->query('SELECT VALOR FROM VALORES WHERE DIVISA ="' . filter_input(INPUT_POST, 'to') . '"')->fetchColumn();
    $result = $from * $to;
    session_start();
    $_SESSION['cantidad'] = filter_input(INPUT_POST, 'cantidad');
    $_SESSION['from'] = filter_input(INPUT_POST, 'from');
    $_SESSION['to'] = filter_input(INPUT_POST, 'to');
    $_SESSION['resultado'] = $result;
    session_write_close();
}
