<?php
require_once 'Cancion.php';

// Obtener géneros disponibles de la clase
$generos = Cancion::getGenerosDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de canciones</title>
</head>
<body>
    <h2>Búsqueda de canciones</h2>

    <form action="buscador.php" method="post">
        <label for="texto">Texto a buscar:</label>
        <input type="text" id="texto" name="texto" required>
        <br><br>

        <input type="radio" name="buscarEn" value="titulo" id="titulo" checked>
        <label for="titulo">Títulos de canción</label>

        <input type="radio" name="buscarEn" value="album" id="album">
        <label for="album">Nombres de álbum</label>

        <input type="radio" name="buscarEn" value="ambos" id="ambos">
        <label for="ambos">Ambos campos</label>

        <br><br>
        <label for="genero">Género:</label>
        <select name="genero" id="genero">
            <?php
            // Generar opciones dinámicamente desde la clase
            foreach ($generos as $valor => $nombre) {
                echo '<option value="' . htmlspecialchars($valor) . '">';
                echo htmlspecialchars($nombre);
                echo '</option>';
            }
            ?>
        </select>
        <br><br>

        <input type="submit" value="Buscar">
    </form>
    
    <br>
    <a href="index.php">⬅️ Volver al inicio</a>
</body>
</html>

<?php
// Procesar búsqueda cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $texto = $_POST['texto'];
    $buscarEn = $_POST['buscarEn'];
    $genero = $_POST['genero'];
    
    // Crear instancia de Cancion
    $cancionObj = new Cancion();
    
    try {
        // Realizar búsqueda usando el método de la clase
        $resultados = $cancionObj->buscar($texto, $buscarEn, $genero);
        
        echo "<h3>Resultados de la búsqueda:</h3>";
        
        if (count($resultados) > 0) {
            foreach ($resultados as $fila) {
                echo htmlspecialchars($fila['titulo']) . "<br>";
            }
        } else {
            echo "<p>No se encontraron resultados para '" . htmlspecialchars($texto) . "'.</p>";
        }
    } catch (Exception $e) {
        echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>