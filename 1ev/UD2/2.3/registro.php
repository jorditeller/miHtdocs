<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>

<body>
    <?php include("cabecera.inc.php"); ?>

    <?php
    $registro_exitoso = false;

    $nombre = $apellido = $usuario = $email = $contrasena = $repetircontrasena = $fecha = $genero = "";
    $condiciones = $publicidad = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["Nombre"] ?? '';
        $apellido = $_POST["Apellido"] ?? '';
        $usuario = $_POST["NombreUsuario"] ?? '';
        $email = $_POST["Email"] ?? '';
        $contrasena = $_POST["Contrasena"] ?? '';
        $repetircontrasena = $_POST["RepetirContrasena"] ?? '';
        $fecha = $_POST["FechaNacimiento"] ?? '';
        $genero = $_POST["Genero"] ?? '';
        $condiciones = isset($_POST["Condiciones"]);
        $publicidad = isset($_POST["Publicidad"]);

        $errores = 0;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "El formato de correo no es válido. ";
            $errores++;
        }
        if ($contrasena !== $repetircontrasena) {
            echo "Las contraseñas deben coincidir. ";
            $errores++;
        }
        if (!$condiciones) {
            echo "Debes aceptar los términos y condiciones. ";
            $errores++;
        }

        if ($errores === 0) {
            $registro_exitoso = true;
        }
    }
    ?>

    <h2>Formulario de Registro</h2>

    <?php if (!$registro_exitoso): ?>
        <form action="registro.php" method="post">

            <label for="nombre">Nombre:</label>
            <input type="text" name="Nombre" id="nombre" value="<?php echo isset($_POST['Nombre']) ? htmlspecialchars($_POST['Nombre']) : ""; ?>" required>
            <br>

            <label for="apellido">Apellido:</label>
            <input type="text" name="Apellido" id="apellido" value="<?php echo isset($_POST['Apellido']) ? htmlspecialchars($_POST['Apellido']): ""?>" required>
            <br>

            <label for="nombreusuario">Nombre de Usuario:</label>
            <input type="text" name="NombreUsuario" id="nombreusuario" value="<?php echo isset ($_POST['NombreUsuario']) ? htmlspecialchars($_POST['NombreUsuario']): ""?>" required>
            <br>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="Email" id="email" value="<?php echo isset ($_POST['Email']) ? htmlspecialchars($_POST['Email']): ""?>" required>
            <br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="Contrasena" id="contrasena" value="<?php echo isset ($_POST['Contrasena']) ? htmlspecialchars($_POST['Contrasena']): ""?>" required>
            <br>

            <label for="repetircontrasena">Repetir Contraseña:</label>
            <input type="password" name="RepetirContrasena" id="repetircontrasena" value="<?php echo isset ($_POST['RepetirContrasena']) ? htmlspecialchars($_POST['RepetirContrasena']): ""?>" required>
            <br>

            <label for="fechanacimiento">Fecha de nacimiento:</label>
            <input type="date" name="FechaNacimiento" id="fechanacimiento" value="<?php echo isset ($_POST['FechaNacimiento']) ? htmlspecialchars($_POST['FechaNacimiento']): ""?>" required>
            <br>

            <label>Género:</label>
            <select name="Genero" required>
                <option value="">...</option>
                <option value="<?php echo isset ($_POST['Genero']) ? htmlspecialchars($_POST['Genero']): ""?>">Masculino</option>
                <option value="<?php echo isset ($_POST['Genero']) ? htmlspecialchars($_POST['Genero']): ""?>">Femenino</option>
            </select>
            <br>

            <label>
                <input type="checkbox" name="Condiciones" required>
                Acepto los términos y condiciones
            </label>
            <br>

            <label>
                <input type="checkbox" name="Publicidad">
                Acepto recibir publicidad
            </label>
            <br>

            <button type="submit">Registrarse</button>
        </form>
    <?php else: ?>
        <p>Registro completado correctamente.</p>
    <?php endif; ?>

    <?php include("footer.inc.php"); ?>
</body>

</html>
