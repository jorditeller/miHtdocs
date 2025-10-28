<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

$codigo_album = $_GET['cod'] ?? null;

if (!$codigo_album) {
    die("No se especificó el álbum al que se añadirá la canción.");
}

$consulta_album = $conexion->prepare("SELECT titulo FROM album WHERE codigo = ?");
$consulta_album->execute([$codigo_album]);
$album = $consulta_album->fetch(PDO::FETCH_ASSOC);

if (!$album) {
    die("Álbum no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo_cancion = $_POST['titulo'] ?? '';
    $posicion_cancion = $_POST['posicion'] ?? '';
    $duracion_cancion = $_POST['duracion'] ?? '';
    $genero_cancion = $_POST['genero'] ?? '';

    $consulta = $conexion->prepare('INSERT INTO cancion (titulo, album, posicion, duracion, genero) 
                                    VALUES (:titulo, :album, :posicion, :duracion, :genero);');
    $consulta->bindParam(':titulo', $titulo_cancion);
    $consulta->bindParam(':album', $codigo_album);
    $consulta->bindParam(':posicion', $posicion_cancion);
    $consulta->bindParam(':duracion', $duracion_cancion);
    $consulta->bindParam(':genero', $genero_cancion);
    $consulta->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Canción</title>
</head>
<body>
    <h1>Añadir Canción al Álbum: <?= $album['titulo'] ?></h1>
    <form action="cancionNueva.php?cod=<?= $codigo_album ?>" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
        <br>
        <label for="posicion">Posición en el álbum:</label>
        <input type="number" id="posicion" name="posicion">
        <br>
        <label for="duracion">Duración (HH:MM:SS):</label>
        <input type="text" id="duracion" name="duracion" required>
        <br>
        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="">--Selecciona--</option>
            <option value="Acustica">Acústica</option>
            <option value="BSO">BSO</option>
            <option value="Blues">Blues</option>
            <option value="Folk">Folk</option>
            <option value="Jazz">Jazz</option>
            <option value="New Age">New Age</option>
            <option value="Pop">Pop</option>
            <option value="Rock">Rock</option>
            <option value="Electrónica">Electrónica</option>
        </select>
        <br>
        <button type="submit">Añadir canción</button>
    </form>
</body>
</html>