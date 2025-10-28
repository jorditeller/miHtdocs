<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Primera prueba php</title>
</head>

<body>
    Este es un archivo php que se encuentra en el servidor.
    <?php
    $num1 = 5;
    $num2 = 10.9;

    $suma = $num1 + (int) $num2;
    print $suma;

    /*include(“archivo . php”);
    include_once(“otro . php”);
    require(“prueba . inc . php”);
    require_once(“inventado . php”);*/
    ?>
</body>

</html>