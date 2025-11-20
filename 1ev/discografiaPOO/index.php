<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<link rel="stylesheet" href="css/estilo.css"/>
		<?php
			include("datos.ini.php");
			include("conexion.ini.php");
			include("album.ini.php");
		?>
	</head>
	<body>
		<?php
			datosDiscografia();
		?>
	</body>
</html>