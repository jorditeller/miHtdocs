<?php
require_once 'Usuario.php';

// Crear instancia de la clase Usuario
$usuario = new Usuario();

// Si ya existe cookie de usuario y no ha respondido aún
if ($usuario->tieneCookie() && !isset($_POST["choice"])) {
    echo '<p>¿Desea conectarse como usuario <strong>' . $usuario->getUsuarioCookie() . '</strong>?</p>';
    echo '<form action="login.php" method="post">';
    echo '<input type="submit" name="choice" value="Sí">';
    echo '<input type="submit" name="choice" value="No">';
    echo '</form>';
    exit;
}

// Si el formulario fue enviado y la respuesta fue SÍ
if (isset($_POST["choice"]) && $_POST["choice"] === "Sí") {
    header("Location: index.php");
    exit;
}

// Si el formulario fue enviado y la respuesta fue NO
if (isset($_POST["choice"]) && $_POST["choice"] === "No") {
    $usuario->eliminarCookie();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Iniciar sesión">
        <br>
        <a href="registro.php">Registro</a>
    </form>
</body>
</html>

<?php
// Validación del formulario de login
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$usuario->tieneCookie() && !isset($_POST["choice"])) {
    $user = $_POST["usuario"];
    $pass = $_POST["password"];
    
    try {
        // Intentar login usando el método de la clase Usuario
        if ($usuario->login($user, $pass)) {
            // Login exitoso: establecer cookie y sesión
            $usuario->setCookie($user);
            session_start();
            $_SESSION["usuario"] = $user;
            header("Location: index.php");
            exit;
        } else {
            echo '<p style="color: red;">Contraseña no válida</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>