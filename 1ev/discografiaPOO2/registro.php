<?php
require_once 'Usuario.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
</head>
<body>
    <form action="registro.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido"><br><br>

        <label for="nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="nacimiento" name="nacimiento"><br><br>

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="archivo">Selecciona una imagen:</label>
        <input type="file" name="archivo" id="archivo" required><br><br>

        <input type="submit" value="Registrarse">
        <br>
        <a href="login.php">Login</a>
    </form>
</body>
</html>

<?php
// Procesar registro cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Crear instancia de Usuario
    $usuarioObj = new Usuario();
    
    try {
        // Intentar registrar usando el método de la clase
        if ($usuarioObj->registrar($_POST, $_FILES['archivo'])) {
            // Registro exitoso, redirigir al login
            header("Location: login.php");
            exit;
        }
    } catch (Exception $e) {
        // Mostrar error si algo falla
        echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>