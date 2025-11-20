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

// Verificar si el usuario está autenticado mediante cookie
if (!isset($_COOKIE['usuario_autenticado'])) {
    header("Location: login.php");
    exit;
}

// Obtener el nombre de usuario desde la cookie
$nombreUsuario = $_COOKIE['usuario_autenticado'];

// Consultar los datos del usuario
try {
    $consulta = $conexion->prepare('SELECT * FROM tabla_usuarios WHERE usuario = ?');
    $consulta->execute([$nombreUsuario]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} catch (PDOException $e) {
    die("Error al obtener datos del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
</head>
<body>
    <h1>Perfil de <?= htmlspecialchars($usuario['usuario']) ?></h1>

    <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($usuario['usuario']) ?></p>

    <?php if (!empty($usuario['imagen_grande'])): ?>
        <p><strong>Imagen de perfil (Grande):</strong></p>
        <img src="<?= htmlspecialchars($usuario['imagen_grande']) ?>" alt="Imagen de perfil" style="max-width:360px;">
    <?php endif; ?>

    <?php if (!empty($usuario['imagen_pequena'])): ?>
        <p><strong>Imagen de perfil (Pequeña):</strong></p>
        <img src="<?= htmlspecialchars($usuario['imagen_pequena']) ?>" alt="Miniatura" style="max-width:72px;">
    <?php endif; ?>

    <br><br>
    <a href="index.php">Volver al inicio</a>
</body>
</html>