<?php
// Configuración para que la conexión use UTF-8
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    // Conexión a la base de datos 'discografia'
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si falla la conexión, se muestra el error y se detiene el script
    die('Falló la conexión: ' . $e->getMessage());
}

// Obtener el código del álbum desde GET o POST
$codigo_album = $_GET['cod'] ?? $_POST['cod'] ?? null;

// Saber si el usuario confirmó la eliminación
$confirmado = $_POST['confirmar'] ?? null;

// Si no se recibió el código del álbum, se detiene el script
if (!$codigo_album) {
    die("No se sabe el código del álbum.");
}

// Buscar el título del álbum para mostrarlo en la confirmación
$consulta = $conexion->prepare("SELECT titulo FROM album WHERE codigo = ?");
$consulta->execute([$codigo_album]);
$album = $consulta->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el álbum, se detiene el script
if (!$album) {
    die("El álbum no existe.");
}

// Si el usuario confirmó la eliminación
if ($confirmado === 'si') {
    try {
        // Iniciar una transacción para asegurar que ambas eliminaciones ocurran juntas
        $conexion->beginTransaction();

        // Eliminar las canciones asociadas al álbum
        $borrarCanciones = $conexion->prepare("DELETE FROM cancion WHERE album = ?");
        $borrarCanciones->execute([$codigo_album]);

        // Eliminar el álbum
        $borrarAlbum = $conexion->prepare("DELETE FROM album WHERE codigo = ?");
        $borrarAlbum->execute([$codigo_album]);

        // Confirmar la transacción
        $conexion->commit();

        // Redirigir al usuario a la página principal
        header("Location: index.php?");
        exit;
    } catch (PDOException $e) {
        // Si ocurre un error, deshacer los cambios
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