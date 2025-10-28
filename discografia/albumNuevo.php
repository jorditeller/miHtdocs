<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

$titulo_album = $_POST['titulo'] ?? '';
$discografica_album = $_POST['discografica'] ?? '';
$formato_album = $_POST['formato'] ?? '';
$fechaLanzamiento_album = $_POST['fechaLanzamiento'] ?? '';
$fechaCompra_album = $_POST['fechaCompra'] ?? '';
$precio_album = $_POST['precio'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = $conexion->prepare('INSERT INTO album (titulo, discografica, formato, fechaLanzamiento, fechaCompra, precio) 
                                    VALUES (:titulo, :discografica, :formato, :fechaLanzamiento, :fechaCompra, :precio);');
    $consulta->bindParam(':titulo', $titulo_album);
    $consulta->bindParam(':discografica', $discografica_album);
    $consulta->bindParam(':formato', $formato_album);
    $consulta->bindParam(':fechaLanzamiento', $fechaLanzamiento_album);
    $consulta->bindParam(':fechaCompra', $fechaCompra_album);
    $consulta->bindParam(':precio', $precio_album);

    try {
        $consulta->execute();
        //correcto
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al añadir el álbum: " . $e->getMessage() . "</p>";
    }
}
?>

<form action="albumNuevo.php" method="POST">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= $titulo_album ?>" required>
    <br>
    <label for="discografica">Discográfica:</label>
    <input type="text" id="discografica" name="discografica" value="<?= $discografica_album ?>" required>
    <br>
    <label for="formato">Formato:</label>
    <select id="formato" name="formato" required>
        <option value="">--Selecciona--</option>
        <option value="Vinilo" <?= $formato_album === 'Vinilo' ? 'selected' : '' ?>>vinilo</option>
        <option value="CD" <?= $formato_album === 'CD' ? 'selected' : '' ?>>cd</option>
        <option value="DVD" <?= $formato_album === 'DVD' ? 'selected' : '' ?>>dvd</option>
        <option value="MP3" <?= $formato_album === 'MP3' ? 'selected' : '' ?>>mp3</option>
    </select>
    <br>
    <label for="fechaLanzamiento">Fecha de lanzamiento: </label>
    <input type="date" id="fechaLanzamiento" name="fechaLanzamiento" value="<?= $fechaLanzamiento_album ?>">
    <br>
    <label for="fechaCompra">Fecha de compra: </label>
    <input type="date" id="fechaCompra" name="fechaCompra" value="<?= $fechaCompra_album ?>">
    <br>
    <label for="precio">Precio: </label>
    <input type="number" id="precio" name="precio" step="0.01" value="<?= $precio_album ?>">
    <br>
    <button type="submit">Añadir álbum</button>
</form>