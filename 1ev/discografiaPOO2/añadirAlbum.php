<?php
require_once 'Album.php';
?>

<form action="añadirAlbum.php" method="post">
    <label for="titulo">Titulo album:</label>
    <input type="text" id="titulo" name="titulo" required>
    <br>
    <input type="submit" value="Añadir">
    <br>
</form>

<?php
// Procesar cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear instancia de Album
    $albumObj = new Album();
    
    try {
        // Intentar crear el álbum
        if ($albumObj->crear($_POST['titulo'])) {
            echo "Album añadido correctamente.";
            // Redirigir al index después de 1 segundo
            header("refresh:1;url=index.php");
        }
    } catch (Exception $e) {
        // Mostrar error si algo falla
        echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>