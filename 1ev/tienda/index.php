<?php
$conexion = new mysqli("localhost", "root", "", "tienda");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$query = "SELECT cod, nombre_corto FROM producto";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Listado de Productos</title>
</head>

<body>
    <h1>Productos disponibles</h1>
    <ul>
        <?php while ($producto = $resultado->fetch_assoc()): ?>
            <li>
                <a href="stock.php?cod=<?= $producto['cod'] ?>">
                    <?= htmlspecialchars($producto['nombre_corto']) ?>
                </a>
            </li>
        <?php
        endwhile;
        $conexion->close();
        ?>
    </ul>
</body>

</html>