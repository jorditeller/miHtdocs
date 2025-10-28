<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

$query = "SELECT codigo, titulo FROM album";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Discos</title>
</head>

<body>
    <h1>Lista de Discos</h1>
    <ul>
        <?php foreach ($resultado as $album): ?>
            <li>
                <a href="disco.php?cod=<?= $album['codigo'] ?>">
                    <?= $album['titulo'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="albumNuevo.php">Añadir álbum</a>
    <br>
    <a href="canciones.php">Busqueda de canciones</a>
</body>
</html>