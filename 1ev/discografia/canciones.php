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

// Recoger los parámetros de búsqueda desde la URL (GET)
$texto = $_GET['textoBuscar'] ?? '';      // Texto que se quiere buscar
$campo = $_GET['campoBuscar'] ?? '';      // Campo en el que buscar (titulo, album, ambos)
$genero = $_GET['genero'] ?? '';          // Género musical
$resultados = [];                         // Array para almacenar los resultados

// Si se ha enviado algún criterio de búsqueda
if (!empty($texto) || !empty($campo) || !empty($genero)) {
    // Consulta base con JOIN entre cancion y album
    $sql = "SELECT cancion.titulo AS cancion, album.titulo AS album, cancion.genero 
            FROM cancion 
            JOIN album ON cancion.album = album.codigo 
            WHERE 1=1"; // Condición inicial para facilitar concatenación

    $params = []; // Array para parámetros de la consulta preparada

    // Filtrar por género si se ha seleccionado
    if (!empty($genero)) {
        $sql .= " AND cancion.genero = :genero";
        $params['genero'] = $genero;
    }

    // Filtrar por texto si se ha indicado campo
    if (!empty($texto) && !empty($campo)) {
        if ($campo === 'titulo') {
            $sql .= " AND LOWER(cancion.titulo) LIKE LOWER(:texto)";
            $params['texto'] = "%$texto%";
        } elseif ($campo === 'album') {
            $sql .= " AND LOWER(album.titulo) LIKE LOWER(:texto)";
            $params['texto'] = "%$texto%";
        } elseif ($campo === 'ambos') {
            $sql .= " AND (LOWER(cancion.titulo) LIKE LOWER(:texto) OR LOWER(album.titulo) LIKE LOWER(:texto))";
            $params['texto'] = "%$texto%";
        }
    }

    // Ejecutar la consulta preparada con los parámetros
    $consulta = $conexion->prepare($sql);
    $consulta->execute($params);
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC); // Obtener resultados como array asociativo
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Canciones</title>
</head>
<body>
    <h1>Búsqueda de canciones</h1>

    <form action="canciones.php" method="GET">
        <label for="textoBuscar">Texto a buscar:</label>
        <input type="text" id="textoBuscar" name="textoBuscar" value="<?= $texto ?>">
        <br>

        <label for="campoBuscar">Buscar en:</label>
        <select id="campoBuscar" name="campoBuscar">
            <option value="">--Selecciona--</option>
            <option value="titulo" <?= $campo === 'titulo' ? 'selected' : '' ?>>Títulos de canción</option>
            <option value="album" <?= $campo === 'album' ? 'selected' : '' ?>>Nombres de álbum</option>
            <option value="ambos" <?= $campo === 'ambos' ? 'selected' : '' ?>>Ambos campos</option>
        </select>
        <br>

        <label for="genero">Género musical:</label>
        <select id="genero" name="genero">
            <option value="">--Selecciona--</option>
            <?php
            $generos = ["Acustica", "BSO", "Blues", "Folk", "Jazz", "New Age", "Pop", "Rock", "Electrónica"];
            foreach ($generos as $g) {
                $selected = $genero === $g ? 'selected' : '';
                echo "<option value=\"$g\" $selected>$g</option>";
            }
            ?>
        </select>
        <br>
        <button type="submit">Buscar</button>
    </form>

    <?php if (!empty($texto) || !empty($campo) || !empty($genero)): ?>
        <?php if (count($resultados) > 0): ?>
            <h2>Resultados encontrados:</h2>
            <ul>
                <?php foreach ($resultados as $fila): ?>
                    <li><?= $fila['cancion'] ?> (<?= $fila['album'] ?>, <?= ($fila['genero']) ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No se encontraron resultados para tu búsqueda.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>