<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    try {
        header("Location: index.php?");
        $verificar = $conexion->prepare('SELECT COUNT(*) FROM tabla_usuarios WHERE usuario = ?');
        $verificar->execute([$usuario]);
        if ($verificar->fetchColumn() > 0) {
            echo "El nombre de usuario ya está registrado.";
        } else {
            $consulta = $conexion->prepare('INSERT INTO tabla_usuarios (usuario, password) VALUES (?, ?)');
            $consulta->execute([$usuario, $hash]);
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
    <form method="POST" action="registro.php">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Registrarse" onclick="window.location.href='index.php'">
    </form>
</body>

</html>