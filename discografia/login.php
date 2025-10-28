<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $conexion = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

//Crear usuario
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

//login Sin Cookie
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
$message = '';
$showLoginForm = true;

if (isset($_COOKIE['usuario_autenticado'])) {
    $nombreUsuario = $_COOKIE['usuario_autenticado'];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['usar_cookie'])) {
        if ($_POST['usar_cookie'] === 'yes') {
            $message = "Access successful";
            $showLoginForm = false;
        } elseif ($_POST['usar_cookie'] === 'no') {
            setcookie('usuario_autenticado', '', time() - 3600);
            $showLoginForm = true;
        }
    } else {
        $showLoginForm = false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        header("Location: index.php?");
        $consulta = $conexion->prepare('SELECT * FROM tabla_usuarios WHERE usuario = ?');
        $consulta->bindParam(1, $username);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            setcookie('usuario_autenticado', $username, time() + 3600);
            header("Location: login.php");
            exit;
        } else {
            $message = 'Login failed';
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

    <?php if (isset($_COOKIE['usuario_autenticado']) && !isset($_POST['usar_cookie'])): ?>
        <form method="POST" action="login.php">
            <p>Do you want to log in as <?php echo $_COOKIE['usuario_autenticado']; ?>?</p>
            <button type="submit" name="usar_cookie" value="yes" onclick="window.location.href='index.php'">Yes</button>
            <button type="submit" name="usar_cookie" value="no">No</button>
        </form>
    <?php elseif ($showLoginForm): ?>
        <form method="POST" action="login.php">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit" onclick="window.location.href='index.php'">Log In</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
