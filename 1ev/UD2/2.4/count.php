<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Count</title>
</head>

<body>
    <?php
    include("cabecera.inc.php");
    ?>
    <?php
    for ($i = 0; $i <= 30; $i++) {
        echo $i . "<br>";
    }

    $factorial = 1;
    $operacion = "";

    for ($i = 5; $i >= 1; $i--) {
        $factorial *= $i;
        $operacion .= $i;
        if ($i > 1) {
            $operacion .= " x ";
        }
    }

    echo "5! = " . $operacion . " = " . $factorial;
    ?>
    
    <?php
    include("footer.inc.php");
    ?>
</body>

</html>