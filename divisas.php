<?php
include_once 'lib.php';
View::start('Divisas');
echo '<h1>Nuestras divisas</h1>';
echo '<a href="index.php">Volver</a>';
echo '<p>A continuación mostramos las divisas para las que ofrecemos cambio actualmente y su valor con respecto al Euro...</p>';
mostrarTabla();
View::end();


function mostrarTabla() {
    $divisas = (new PDO('sqlite:./ftsi.db'))->query('SELECT DIVISA,NOMBRE,SIMBOLO FROM DIVISAS');
    $aux = '';
    foreach ($divisas as $value) {
        $valor = (new PDO('sqlite:./ftsi.db'))->query('SELECT VALOR FROM VALORES WHERE DIVISA ="' . $value['divisa'] . '"')->fetchColumn();
        $aux = $aux . "<tr><td>$value[divisa]</td><td>$value[nombre]</td><td>$valor</td><td>Valor</td></tr>";
    }
    $tabla = "<div id='tabla'><table><tr><th>Divisa</th><th>Nombre</th><th>Símbolo</th><th>Valor</th></tr>$aux</table></div>";
    echo $tabla;
}