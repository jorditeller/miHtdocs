<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

$codigo_album = $_GET['cod'] ?? $_POST['cod'] ?? null;
$confirmado = $_POST['confirmar'] ?? null;

if (!$codigo_album) {
    die("No se sabe el código del álbum.");
}

$consulta = $conexion->prepare("SELECT titulo FROM album WHERE codigo = ?");
$consulta->execute([$codigo_album]);
$album = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$album) {
    die("El álbum no existe.");
}

if ($confirmado === 'si') {
    try {
        $conexion->beginTransaction();

        $borrarCanciones = $conexion->prepare("DELETE FROM cancion WHERE album = ?");
        $borrarCanciones->execute([$codigo_album]);

        $borrarAlbum = $conexion->prepare("DELETE FROM album WHERE codigo = ?");
        $borrarAlbum->execute([$codigo_album]);

        $conexion->commit();

        header("Location: index.php?");
        exit;
    } catch (PDOException $e) {
        $conexion->rollBack();
        die("Error al borrar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación</title>
</head>
<body>
    <h1>Confirmación</h1>
    <p>¿Estás seguro de que quieres borrar el álbum <strong><?= $album['titulo'] ?></strong> y todas sus canciones?</p>

    <form method="POST">
        <input type="hidden" name="confirmar" value="si">
        <input type="hidden" name="cod" value="<?= $codigo_album ?>">
        <button type="submit" onclick="window.location.href='index.php'">Sí, borrar</button>
        <button type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </form>
</body>
</html>