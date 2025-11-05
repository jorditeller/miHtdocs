<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
		include("datos.ini.php");
        include("conexion.ini.php");
        include("album.ini.php");
        include("cancion.ini.php");
	?>
    <title>Document</title>
</head>
<body>
    <?php
        $album = new Album($_GET['cod'],'','','','','','');
        datosDisco($album);
    ?>
</body>
</html>