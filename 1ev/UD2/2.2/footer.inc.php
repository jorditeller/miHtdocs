<footer>
    <h3>Jordi Teller</h3>
    <?php
    /*
    $dia = date("w");
    $nombreDia = "";
    switch ($dia) {
        case 0:
            $nombreDia = "Domingo";
            break;
        case 1:
            $nombreDia = "Lunes";
            break;
        case 2:
            $nombreDia = "Martes";
            break;
        case 3:
            $nombreDia = "Miércoles";
            break;
        case 4:
            $nombreDia = "Jueves";
            break;
        case 5:
            $nombreDia = "Viernes";
            break;
        case 6:
            $nombreDia = "Sábado";
            break;
    }

    $mes = date("n");
    $nombreMes = "";
    switch ($mes) {
        case 1:
            $nombreMes = "Enero";
            break;
        case 2:
            $nombreMes = "Febrero";
            break;
        case 3:
            $nombreMes = "Marzo";
            break;
        case 4:
            $nombreMes = "Abril";
            break;
        case 5:
            $nombreMes = "Mayo";
            break;
        case 6:
            $nombreMes = "Junio";
            break;
        case 7:
            $nombreMes = "Julio";
            break;
        case 8:
            $nombreMes = "Agosto";
            break;
        case 9:
            $nombreMes = "Septiembre";
            break;
        case 10:
            $nombreMes = "Octubre";
            break;
        case 11:
            $nombreMes = "Noviembre";
            break;
        case 12:
            $nombreMes = "Diciembre";
            break;
    }

    echo $nombreDia . ", " . date("d") . " de " . $nombreMes . " de " . date("Y");
    */

    $dia = date("w");
    $mes = date("n");

    $array = [
        [
            0 => "Domingo",
            1 => "Lunes",
            2 => "Martes",
            3 => "Miércoles",
            4 => "Jueves",
            5 => "Viernes",
            6 => "Sábado"
        ],
        [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ]
    ];

    echo $array[0][$dia] . ", " . date("d") . " de " .  $array[1][$mes] . " de " . date("Y");

    ?>

</footer>