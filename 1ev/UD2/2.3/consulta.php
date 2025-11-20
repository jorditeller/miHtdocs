<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
</head>

<body>
    <?php
    include("cabecera.inc.php");
    ?>
    <form action="consulta.php" method="post">
        <label for="Nombre">Nombre: </label>
        <input type="text" name="nombre" id="nombre">
        <label for="Fecha">Fecha: </label>
        <input type="date" name="fecha" id="fecha">
        <input type="submit" value="Enviar">
    </form>

    <?php
    include("footer.inc.php");
    ?>

</body>

</html>