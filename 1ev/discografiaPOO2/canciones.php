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
    <title>Agregar canción</title>
</head>
<body>
    <h2>Agregar nueva canción</h2>
    
    <form action="agregar.php" method="post">
        <br>
        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo" required>
        <br>
        
        <label for="minutos">Minutos:</label>
        <input type="number" id="minutos" name="minutos" min="0" max="59" step="1" required>
        
        <label for="segundos">Segundos:</label>
        <input type="number" id="segundos" name="segundos" min="0" max="59" step="1" required>
        <br>
        
        <label>
            Género:
            <select name="genero">
                <?php
                // Generar opciones dinámicamente desde la clase
                foreach ($generos as $valor => $nombre) {
                    echo '<option value="' . htmlspecialchars($valor) . '">';
                    echo htmlspecialchars($nombre);
                    echo '</option>';
                }
                ?>
            </select>
        </label>
        <br>

        <!-- Campo oculto para mantener el código del álbum -->
        <?php
        if (isset($_GET['cod'])) {
            echo '<input type="hidden" name="cod" value="' . htmlspecialchars($_GET['cod']) . '">';
        }
        ?>

        <input type="submit" value="Agregar">
        <br>
    </form>

    <?php
    // Procesar cuando se envía el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Crear instancia de Cancion
        $cancionObj = new Cancion();
        
        try {
            // Intentar agregar la canción
            if ($cancionObj->agregar($_POST)) {
                echo '<p style="color: green;">Canción agregada correctamente.</p>';
                echo '<a href="canciones.php?cod=' . htmlspecialchars($_POST['cod']) . '">Ver canciones del álbum</a>';
            }
        } catch (Exception $e) {
            // Mostrar error si algo falla
            echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    ?>
</body>
</html>