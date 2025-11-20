<?php
require_once 'Usuario.php';
require_once 'Album.php';

// Crear instancias de las clases
$usuarioObj = new Usuario();
$albumObj = new Album();

// Verificar si hay cookie de usuario
if (!$usuarioObj->tieneCookie()) {
    header("Location: login.php");
    exit;
}

// Obtener nombre de usuario de la cookie
$nombreUsuario = $usuarioObj->getUsuarioCookie();

// Obtener rutas de imágenes del usuario
try {
    $imagenes = $usuarioObj->getRutasImagenes($nombreUsuario);
    $rutaMini = $imagenes['mini'];
    $rutaGrande = $imagenes['grande'];
} catch (Exception $e) {
    // Si hay error, usar imágenes por defecto
    $rutaMini = 'img/default-mini.jpg';
    $rutaGrande = 'img/default-grande.jpg';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h1>
        <a href="<?php echo htmlspecialchars($rutaGrande); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($rutaMini); ?>" 
                 alt="Foto de usuario" 
                 width="150" 
                 style="border-radius: 10px; border: 2px solid #333;">
        </a>
    </header>

    <hr>
    <h2>Listado de álbumes</h2>
    
    <?php
    try {
        // Obtener todos los álbumes usando el método de la clase
        $albumes = $albumObj->obtenerTodos();
        
        // Mostrar cada álbum
        foreach ($albumes as $album) {
            echo '<p>';
            echo '<a href="canciones.php?cod=' . htmlspecialchars($album["codigo"]) . '">';
            echo 'Título: ' . htmlspecialchars($album["titulo"]);
            echo '</a> | ';
            echo '<a href="eliminarAlbum.php?cod=' . htmlspecialchars($album["codigo"]) . '">🗑️</a>';
            echo '</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;">Error al cargar álbumes: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
    
    <br>
    <a href="añadirAlbum.php">➕ Añadir nuevo álbum</a>
</body>
</html>