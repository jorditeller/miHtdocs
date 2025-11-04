<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

// Crear usuario manualmente (comentado)
/*
$usuario = 'admin';
$pass = 'admin123';
$hash = password_hash($pass, PASSWORD_DEFAULT);

try {
    $consulta = $conexion->prepare('INSERT INTO tabla_usuarios (usuario, password) VALUES (?, ?)');
    $consulta->bindParam(1, $usuario);
    $consulta->bindParam(2, $hash);
    $consulta->execute();
} catch (PDOException $e) {
    echo "Error al insertar: " . $e->getMessage();
}
*/


// Login básico sin cookies (comentado)
/*
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $consulta = $conexion->prepare('SELECT * FROM tabla_usuarios WHERE usuario = ?');
        $consulta->bindParam(1, $username);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $message = 'Login successful';
        } else {
            $message = 'Login failed';
        }
    } catch (PDOException $e) {
        $message = "Error al comprobar el login: " . $e->getMessage();
    }
}
*/


//login Con Cookies y form extra
// Mensaje de estado y control de formulario
$message = '';
$showLoginForm = true;

// Si ya existe la cookie de sesión
if (isset($_COOKIE['usuario_autenticado'])) {
    $nombreUsuario = $_COOKIE['usuario_autenticado'];

    // Si el usuario responde al formulario de confirmación
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['usar_cookie'])) {
        if ($_POST['usar_cookie'] === 'yes') {
            header("Location: index.php");
            exit;
        } elseif ($_POST['usar_cookie'] === 'no') {
            setcookie('usuario_autenticado', '', time() - 3600);
            $showLoginForm = true;
        }
    } else {
        // Mostrar formulario de confirmación
        $showLoginForm = false;
    }
}

// Si se envió el formulario de login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $consulta = $conexion->prepare('SELECT * FROM tabla_usuarios WHERE usuario = ?');
        $consulta->bindParam(1, $username);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            setcookie('usuario_autenticado', $username, time() + 3600);
            header("Location: index.php");
            exit;
        } else {
            $message = 'Login fallido';
        }
    } catch (PDOException $e) {
        $message = "Error al comprobar el login: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Si hay cookie y no se ha respondido aún -->
    <?php if (isset($_COOKIE['usuario_autenticado']) && !isset($_POST['usar_cookie'])): ?>
        <form method="POST" action="login.php">
            <p>¿Quieres iniciar sesión como <strong><?= $_COOKIE['usuario_autenticado'] ?></strong>?</p>
            <button type="submit" name="usar_cookie" value="yes">Sí</button>
            <button type="submit" name="usar_cookie" value="no">No</button>
        </form>

    <!-- Si no hay cookie o se ha rechazado -->
    <?php elseif ($showLoginForm): ?>
        <form method="POST" action="login.php">
            <label>Usuario:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Iniciar sesión</button>
        </form>
    <?php endif; ?>

    <!-- Mensaje de estado -->
    <?php if (!empty($message)): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>
</body>
</html>

