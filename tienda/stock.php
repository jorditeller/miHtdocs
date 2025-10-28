<?php
$conexion = new mysqli("localhost", "root", "", "tienda");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

try {
    $cod = $_GET['cod'];
    $producto = null;

    if ($cod) {
        $resultado = $conexion->query(
            "SELECT producto.nombre_corto, stock.unidades
            FROM producto
            JOIN stock ON producto.cod = stock.producto
            WHERE producto.cod = '$cod'"
        );

        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nuevasUnidades = $_POST['unidades'];
        $codigoProducto = $_POST['cod'];

        $conexion->autocommit(false);
        $conexion->begin_transaction();

        try {
            $conexion->query("UPDATE stock SET unidades = $nuevasUnidades WHERE producto = '$codigoProducto'");
            $conexion->commit();
            echo "<p>Unidades actualizadas correctamente</p>";

        } catch (Exception $e) {
            $conexion->rollback();
            echo "<p>Error al actualizar las unidades.</p>";
        }
        $conexion->autocommit(true);
    }
} catch (Exception $e) {
    echo "<p>Error al conectar o consultar la base de datos.</p>";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Stock del producto</title>
</head>

<body>
    <h1>Stock del producto</h1>

    <?php if ($producto): ?>
        <p><strong>Producto:</strong> <?= $producto['nombre_corto'] ?></p>
        <form action="stock.php?cod=<?= $cod ?>" method="POST">
            <input type="hidden" name="cod" value="<?= $cod ?>">
            <label for="unidades">Unidades:</label>
            <input type="number" id="unidades" name="unidades" value="<?= $producto['unidades'] ?>" required>
            <button type="submit">Actualizar</button>
        </form>
    <?php else: ?>
        <p>No se encontró el producto con código <?= $cod ?></p>
    <?php endif; ?>

    <?php $conexion->close(); ?>
</body>
</html>