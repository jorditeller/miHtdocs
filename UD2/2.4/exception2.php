<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exception2</title>
</head>
<body>
    <?php
    class DivisionException extends Exception {
        public function errorMessage()
        {
            return "Error: " . $this->getMessage();
        }
    } 
    function dividir($a, $b){
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new DivisionException("Ambos valores deben ser numéricos.");
        }
        if ($b == 0) {
            throw new DivisionException("No se puede dividir entre cero.");
        }
        return $a / $b; 
    }

    $resultado = null;
    $error = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num1 = $_POST["numero1"] ?? '';
        $num2 = $_POST["numero2"] ?? '';

        try {
            $resultado = dividir($num1, $num2);
        } catch (DivisionException $e) {
            $error = $e->errorMessage();
        }
    }
    ?>

    <form method="post">
        <label for="numero1">Número 1:</label>
        <input type="text" name="numero1" id="numero1" required>
        <label for="numero2">Número 2:</label>
        <input type="text" name="numero2" id="numero2" required>
        <button type="submit">Dividir</button>
    </form>

    <?php
    if ($resultado !== null) {
        echo "$resultado";
    }
    if ($error !== null) {
        echo "$error";
    }
    ?>
</body>

</html>