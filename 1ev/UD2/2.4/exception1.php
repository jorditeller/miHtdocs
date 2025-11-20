<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exception1</title>
</head>
<body>
    <?php 
    function sumar($a, $b) {
        if (!is_numeric(value: $a) || !is_numeric(value: $b)) {
            throw new Exception(message: "Ambos valores deben ser numéricos.");
        }
        return $a + $b;
    }

    $resultado = "";
    $error = "";

    try {
        $numero1 = $_POST["numero1"] ?? "";
        $numero2 = $_POST["numero2"] ?? "";
            $resultado = sumar(a: $numero1, b: $numero2);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    ?>
    <form method="post">
        <label for="numero1">Numero 1: </label>
        <input type="text" name="numero1" id="numero1">

        <label for="numero2">Numero 2: </label>
        <input type="text" name="numero2" id="numero2">

        <button type="submit">Sumar</button>
    </form>

    <?php
    if ($resultado !== null) {
        echo "$resultado";
    }
    ?>

</body>
</html>