<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

function resizeImage($src, $dest, $width, $height) {
    list($origWidth, $origHeight) = getimagesize($src);
    $image_p = imagecreatetruecolor($width, $height);
    $image = mime_content_type($src) === 'image/jpeg' ? imagecreatefromjpeg($src) : imagecreatefrompng($src);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
    mime_content_type($src) === 'image/jpeg' ? imagejpeg($image_p, $dest) : imagepng($image_p, $dest);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    // Validar imagen
    $imagen = $_FILES['imagen'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($imagen['tmp_name']);
    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
        die('Formato de imagen no permitido.');
    }

    list($width, $height) = getimagesize($imagen['tmp_name']);
    if ($width > 360 || $height > 480) {
        die('La imagen excede el tamaño máximo permitido (360x480px).');
    }

    try {
        $verificar = $conexion->prepare('SELECT COUNT(*) FROM tabla_usuarios WHERE usuario = ?');
        $verificar->execute([$usuario]);
        if ($verificar->fetchColumn() > 0) {
            echo "El nombre de usuario ya está registrado.";
        } else {
            // Crear carpeta de usuario
            $ruta = "img/users/$usuario";
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }

            // Guardar imágenes
            $bigPath = "$ruta/idUserBig.png";
            $smallPath = "$ruta/idUserSmall.png";
            resizeImage($imagen['tmp_name'], $bigPath, 360, 480);
            resizeImage($imagen['tmp_name'], $smallPath, 72, 96);

            // Insertar en BD
            $consulta = $conexion->prepare('INSERT INTO tabla_usuarios (usuario, password, imagen_grande, imagen_pequena) VALUES (?, ?, ?, ?)');
            $consulta->execute([$usuario, $hash, $bigPath, $smallPath]);

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