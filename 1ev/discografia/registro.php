<?php
// Configuración de conexión con UTF-8
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    // Conexión a la base de datos
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

// Función para redimensionar imagen
function redimensionarImagen($origen, $destino, $ancho, $alto)
{
    list($anchoOriginal, $altoOriginal) = getimagesize($origen);
    $imagenFinal = imagecreatetruecolor($ancho, $alto);

    $tipo = mime_content_type($origen);
    $imagenOriginal = ($tipo === 'image/jpeg') ? imagecreatefromjpeg($origen) : imagecreatefrompng($origen);

    imagecopyresampled($imagenFinal, $imagenOriginal, 0, 0, 0, 0, $ancho, $alto, $anchoOriginal, $altoOriginal);

    ($tipo === 'image/jpeg') ? imagejpeg($imagenFinal, $destino) : imagepng($imagenFinal, $destino);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $imagen = $_FILES['imagen'];
    $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($imagen['tmp_name']);

    // Validar tipo de imagen
    if (!in_array($tipo, ['image/jpeg', 'image/png'])) {
        die('Solo se permiten imágenes JPG o PNG.');
    }

    try {
        // Verificar si el usuario ya existe
        $verificar = $conexion->prepare('SELECT COUNT(*) FROM tabla_usuarios WHERE usuario = ?');
        $verificar->execute([$usuario]);

        if ($verificar->fetchColumn() > 0) {
            echo "El nombre de usuario ya está registrado.";
        } else {
            // Crear carpeta del usuario si no existe
            $carpeta = "img/users/$usuario";
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            // Rutas de las imágenes
            $rutaGrande = "$carpeta/perfil_grande.png";
            $rutaPequena = "$carpeta/perfil_pequeno.png";

            // Guardar imagen redimensionada
            redimensionarImagen($imagen['tmp_name'], $rutaGrande, 360, 480);
            redimensionarImagen($imagen['tmp_name'], $rutaPequena, 72, 96);

            // Insertar usuario en la base de datos
            $insertar = $conexion->prepare('INSERT INTO tabla_usuarios (usuario, password, imagen_grande, imagen_pequena) VALUES (?, ?, ?, ?)');
            $insertar->execute([$usuario, $hash, $rutaGrande, $rutaPequena]);

            echo "Registro exitoso.";
        }
    } catch (PDOException $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>

<body>
    <form method="POST" action="registro.php" enctype="multipart/form-data">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <label for="imagen">Imagen de perfil (JPG o PNG):</label>
        <input type="file" name="imagen" accept="image/jpeg,image/png" required><br>

        <input type="submit" value="Registrarse">
    </form>
</body>

</html>