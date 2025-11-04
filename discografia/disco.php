<?php
// Configuración para que la conexión use UTF-8
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    // Conexión a la base de datos 'discografia'
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} catch (PDOException $e) {
    // Si falla la conexión, se muestra el error y se detiene el script
    die('Falló la conexión: ' . $e->getMessage());
}

// Obtener el código del álbum desde la URL (GET)
$codigo = $_GET['cod'];

// Si se recibió el código del álbum
if ($codigo) {
    // Consultar los datos del álbum
    $resultado = $conexion->prepare("SELECT * FROM album WHERE codigo = ?");
    $resultado->execute([$codigo]);
    $album = $resultado->fetch(PDO::FETCH_ASSOC);

    // Consultar las canciones asociadas al álbum
    $resultadotCanciones = $conexion->prepare("SELECT titulo, duracion, genero FROM cancion WHERE album = ?");
    $resultadotCanciones->execute([$codigo]);
    $canciones = $resultadotCanciones->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no se recibió el código, se detiene el script
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