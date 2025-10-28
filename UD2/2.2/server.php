<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server</title>
</head>

<body>
    <?php
    include("cabecera.inc.php");
    ?>
    <div>
        <table border="1">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SERVER as $key => $value) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    echo "<td>$value</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    include("footer.inc.php");
    ?>

</body>

</html>