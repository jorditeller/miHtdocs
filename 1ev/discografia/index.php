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

// Consulta SQL para obtener el código y título de todos los álbumes
$query = "SELECT codigo, titulo FROM album";

// Ejecutar la consulta y guardar el resultado
$resultado = $conexion->query($query);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Discos</title>
</head>

<body>
    <h1>Lista de Discos</h1>
    <ul>
        <?php foreach ($resultado as $album): ?>
            <li>
                <a href="disco.php?cod=<?= $album['codigo'] ?>">
                    <?= $album['titulo'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="albumNuevo.php">Añadir álbum</a>
    <br>
    <a href="canciones.php">Busqueda de canciones</a>
</body>
</html>