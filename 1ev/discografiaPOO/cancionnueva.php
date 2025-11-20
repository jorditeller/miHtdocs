<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
		include("datos.ini.php");
        include("conexion.ini.php");
        include("cancion.ini.php");
	?>
    <title>Document</title>
</head>
<body>
    <?php
        $cancion = new Cancion($_GET['titulo'],$_GET['cod'],'','','');
        formularioCancion($cancion);
    ?>
</body>
</html>