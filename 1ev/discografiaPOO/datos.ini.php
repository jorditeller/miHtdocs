<?php
	function formularioDisco(){//Esta función imprimirá en la página un formulario y llamará a la función registrar del objeto para registrar un disco nuevo
		echo '<button  onclick=location.href="./index.php">Volver</button>';
		echo '<h1>Crear nuevo disco</h1>';
		echo '<form action="disconuevo.php" method="post">';
		echo '<input type="text" required name="titulo" placeholder="Título"/>';
		echo '<input type="text" required name="discografia" placeholder="Discografía"/>';
		echo '<label>formato: </label>';
		echo '<select name="formato">
			<option> vinilo</option>
			<option> cd</option>
			<option> dvd</option>
			<option> mp3</option>
			</select>';
		echo '<label>fechaLanzamiento: </label>';
		echo '<input type="date" name="fechaLanzamiento"/>';
		echo '<label>fechaCompra: </label>';
		echo '<input type="date" name="fechaCompra"/>';
		echo '<input type="number" step="  " min=0 value=0 name="precio" placeholder="precio"/>';
		echo '<input id="reg-mod" type="submit" value="Registrar"/>';
		echo '</form>';
		
		if(isset($_POST["titulo"])){
			$conectar = new Conexion('localhost','user','user','discografia');
			$conexion = $conectar->conectionPDO();
			$album = new Album('',$_POST['titulo'],$_POST['discografia'],$_POST['formato'],$_POST['fechaLanzamiento'],$_POST['fechaCompra'],$_POST['precio']);

			$album->registrarDisco($conexion);
		}
	}

	function formularioCancion($cancion){//Esta función imprimirá en la página un formulario y llamará a la función registrar del objeto para registrar una cancion nueva
		echo '<button  onclick=location.href="./index.php">Volver</button>';
		echo '<h1>Crear nueva canción</h1>';
		echo '<form action="cancionnueva.php?cod='.$cancion->getAlbum().'&titulo='.$cancion->getTitulo().'" method="post">';
		echo '<input type="text" required name="titulo" placeholder="Título" />';
		echo '<label>Album: </label>';
		echo '<input type="text" required name="album" value="'.$cancion->getTitulo().'" readonly/>';
		echo '<label>Posición: </label>';
		echo '<input type="number" min=0 name="posicion" value=0 />';
		echo '<label>Duración: </label>';
		echo '<input type="time" name="duracion" step="1"/>';
		echo '<label>Género: </label>';
		echo '<select name="genero">
			<option> Acustica</option>
			<option> BSO</option>
			<option> Blues</option>
			<option> Folk</option>
			<option> Jazz</option>
			<option> New age</option>
			<option> Pop</option>
			<option> Rock</option>
			<option> Electronica</option>
			</select>';
		echo '<input id="reg-mod" type="submit" value="Registrar"/>';
		echo '</form>';
		if(isset($_POST["titulo"])){
			$conectar = new Conexion('localhost','user','user','discografia');
			$conexion = $conectar->conectionPDO();
			$cancion = new Cancion($_POST['titulo'],$cancion->getAlbum(),$_POST['posicion'],$_POST['duracion'],$_POST['genero']);
			$cancion->registrarCancion($conexion);
		}
	}

	function formularioBuscarCancion(){//Esta función imprimirá en la página un formulario y llamará a la función datosBuacados para devolver una lista de canciones
		echo '<button  onclick=location.href="./index.php">Volver</button>';
		echo '<h1>Búsqueda de canciones</h1>';
		echo '<form action="canciones.php" method="post">';
		echo 'Texto a buscar: ';
		echo '<input type="text" required name="textoBuscar"/>';
		echo '<div>';
		echo 'Buscar en: ';
		echo '<input type="radio" id=tc name="select" checked value="cancion.titulo"/>';
		echo '<label for="tc">Títulos de canción </label>';
		echo '<input type="radio" id="na" name="select" value="album.titulo"/>';
		echo '<label for="na">Nombres álbum </label>';
		echo '<input type="radio" id="ca" name="select" value="album.titulo = cancion.titulo and cancion.titulo"/>';
		echo '<label for="ca">Ambos campos </label>';
		echo '</div>';
		echo '<div>';
		echo 'Genero musical: ';
		echo '<select name="genero">
		<option> Acustica</option>
		<option> BSO</option>
		<option> Blues</option>
		<option> Folk</option>
		<option> Jazz</option>
		<option> New age</option>
		<option> Pop</option>
		<option> Rock</option>
		<option> Electronica</option>
		</select>';
		echo '</div>';
		echo '<input id="reg-mod" type="submit" value="Buscar"/>';
		echo '</form>';
		if(isset($_POST["textoBuscar"])){
			datosBuscados($_POST['textoBuscar'],$_POST['select'],$_POST['genero']);
			//registrarDisco($_POST['titulo'],$_POST['discografia'],$_POST['formato'],$_POST['fechaLanzamiento'],$_POST['fechaCompra'],$_POST['precio']);
			
		}
	}

	function datosDiscografia(){//devuelve una lista de Albums
		$conectar = new Conexion('localhost','user','user','discografia');
		$conexion = $conectar->conectionPDO();
		$resultado = $conexion->query('SELECT cod,titulo,discografia,formato,fechaLanzamiento,fechaCompra,precio FROM discografia.album;');
		echo'<button  onclick=location.href="./disconuevo.php">Nuevo disco</button>';
		echo'<button  onclick=location.href="./canciones.php">Buscar canciones</button>';
		echo'<table>';
		echo'<tr>
			<th>título</th>
			<th>Discografía</th>
			<th>formato</th>
			<th>fechaLanzamiento</th>
			<th>fechaCompra</th>
			<th>Precio</th>			
		</tr>';
		while ($registro = $resultado->fetch()) {
			$album = new Album($registro['cod'],$registro['titulo'],$registro['discografia'],$registro['formato'],$registro['fechaLanzamiento'],$registro['fechaCompra'],$registro['precio']); 
			echo '<tr>';
			echo '<td><a href="http://localhost/discografia/disco.php?cod='.$album->getCod().'">'.$album->getTitulo().'</a></td>';
			echo '<td>'.$album->getDiscografia().'</td>';
			echo '<td>'.$album->getFormato().'</td>';
			echo '<td>'.$album->getFechaL().'</td>';
			echo '<td>'.$album->getFechaC().'</td>';
			echo '<td>'.$album->getPrecio().'</td>';
			echo '<th id="botonInsertar" ><button  onclick=location.href="./cancionnueva.php?cod='.$registro['cod'].'&titulo='.$registro['titulo'].'">Canción Nueva</button></th>';
			echo '</tr>';
		}
		echo'</table>';
	}

	function datosDisco($album){//Devuelve los datos del album seleccionado
		$conectar = new Conexion('localhost','user','user','discografia');
		$conexion = $conectar->conectionPDO();
		$resultado = $conexion->query('SELECT count(titulo) as totalCanciones FROM discografia.cancion WHERE cancion.album = '.$album->getCod().';');
		while ($registro = $resultado->fetch()) {
			$TC = $registro['totalCanciones'];
		}
		$resultado = $conexion->query('SELECT cod,titulo,discografia,formato,fechaLanzamiento,fechaCompra,precio FROM discografia.album WHERE album.cod = '.$album->getCod().';');
		echo '<button  onclick=location.href="./index.php">Volver</button>';
		echo '<h1>DATOS DEL DISCO</h1>';
		echo'<table>';
		echo'<tr>
			<th>Código</th>
			<th>título</th>
			<th>Discografía</th>
			<th>formato</th>
			<th>fechaLanzamiento</th>
			<th>fechaCompra</th>
			<th>Precio</th>			
		</tr>';
		while ($registro = $resultado->fetch()) {
			$listaAlbum = new Album($registro['cod'],$registro['titulo'],$registro['discografia'],$registro['formato'],$registro['fechaLanzamiento'],$registro['fechaCompra'],$registro['precio']);
			echo '<tr>';
			echo '<td>'.$listaAlbum->getCod().'</td>';
			echo '<td>'.$listaAlbum->getTitulo().'</td>';
			echo '<td>'.$listaAlbum->getDiscografia().'</td>';
			echo '<td>'.$listaAlbum->getFormato().'</td>';
			echo '<td>'.$listaAlbum->getFechaL().'</td>';
			echo '<td>'.$listaAlbum->getFechaC().'</td>';
			echo '<td>'.$listaAlbum->getPrecio().'</td>';
			echo '<th id="botonBorrar" ><button  onclick=location.href="./borrardisco.php?cod='.$listaAlbum->getCod().'&TC='.$TC.'">Borrar disco</button></th>';
			echo '</tr>';
		}
		echo'</table>';

		datosCancion($album->getCod());
	}
	function datosCancion($cod){//devuelve los datos de todas las canciones del album pasado
		$conectar = new Conexion('localhost','user','user','discografia');
		$conexion = $conectar->conectionPDO();
		$resultado = $conexion->query('SELECT * FROM discografia.cancion WHERE album = '.$cod.';');
		echo'<h3>CANCIONES DEL DISCO</h3>';
		echo'<table>';
		echo'<tr>
			<th>título</th>
			<th>Album</th>
			<th>posicion</th>
			<th>duracion</th>
			<th>genero</th>			
		</tr>';
		while ($registro = $resultado->fetch()) {
			$listaCanciones = new Cancion($registro['titulo'],$registro['album'],$registro['posicion'],$registro['duracion'],$registro['genero']);
			echo '<tr>';
			echo '<td>'.$listaCanciones->getTitulo().'</td>';
			echo '<td>'.$listaCanciones->getAlbum().'</td>';
			echo '<td>'.$listaCanciones->getPosicion().'</td>';
			echo '<td>'.$listaCanciones->getDuracion().'</td>';
			echo '<td>'.$listaCanciones->getGenero().'</td>';
			echo '</tr>';
		}
		echo'</table>';
	}

	function datosBuscados($textoBuscar, $select, $genero){//Esta función devuelve una lista de canciones dependiendo de los datos que quiera utilizar el usuario para su busqueda
		$conectar = new Conexion('localhost','user','user','discografia');
		$conexion = $conectar->conectionPDO();
		$resultado1 = $conexion->query('SELECT count(cancion.titulo) as cont FROM discografia.cancion,discografia.album WHERE album.cod = cancion.album and cancion.genero = "'.$genero.'" and '.$select.' LIKE "%'.$textoBuscar.'%";');
		
		$contar = $resultado1->fetch();
		if($contar['cont'] > 0) {
			$resultado2 = $conexion->query('SELECT cancion.titulo as titulo ,album.titulo as album, cancion.posicion, cancion.duracion, cancion.genero   FROM discografia.cancion,discografia.album WHERE album.cod = cancion.album and cancion.genero = "'.$genero.'" and '.$select.' LIKE "%'.$textoBuscar.'%";');
		echo'<table>';
		echo'<tr>
			<th>título</th>
			<th>Album</th>
			<th>posicion</th>
			<th>duracion</th>
			<th>genero</th>			
		</tr>';
		while ($registro = $resultado2->fetch()) {
			$Canciones = new Cancion($registro['titulo'],$registro['album'],$registro['posicion'],$registro['duracion'],$registro['genero']);
			echo '<tr>';
			echo '<td>'.$Canciones->getTitulo().'</td>';
			echo '<td>'.$Canciones->getAlbum().'</td>';
			echo '<td>'.$Canciones->getPosicion().'</td>';
			echo '<td>'.$Canciones->getDuracion().'</td>';
			echo '<td>'.$Canciones->getGenero().'</td>';
			echo '</tr>';
		}
		echo'</table>';
		}else{
			echo'<h1>NO SE ENCONTRARON RESULTADOS!</h1>';
		}
	}
?>