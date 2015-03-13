<?php
class View{
    public static function  start($title){
        $html = "<!DOCTYPE html>
<html>
<head>
<meta charset=\"utf-8\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"estilos.css\">
<title>$title</title>
</head>
<body>";
        echo $html;
    }
    public static function end(){
        echo '<a href=index.php><img src="logo.jpg"></img></a>';
        echo '</body>
</html>';
    }
}
