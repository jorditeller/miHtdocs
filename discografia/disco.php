<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

$codigo = $_GET['cod'];

if ($codigo) {
    $resultado = $conexion->prepare("SELECT * FROM album WHERE codigo = ?");
    $resultado->execute([$codigo]);
    $album = $resultado->fetch(PDO::FETCH_ASSOC);

    $resultadotCanciones = $conexion->prepare("SELECT titulo, duracion, genero FROM cancion WHERE album = ?");
    $resultadotCanciones->execute([$codigo]);
    $canciones = $resultadotCanciones->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Código de álbum no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Disco</title>
</head>

<body>
    <h1><?= $album['titulo'] ?></h1>
    <p><strong>Año:</strong> <?= $album['fechaLanzamiento'] ?></p>
    <p><strong>Artista:</strong> <?= $album['discografica'] ?></p>

    <h2>Canciones</h2>
    <ul>
        <?php foreach ($canciones as $cancion): ?>
            <li>
                <?= $cancion['titulo'] . ', ' ?>
                <?= $cancion['duracion'] . ', ' ?>
                <?= $cancion['genero'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="cancionNueva.php?cod=<?= $album['codigo'] ?>">Añadir canción</a>
    <br>
    <a href="borrarAlbum.php?cod=<?= $album['codigo'] ?>">Borrar álbum</a>
</body>

</html>