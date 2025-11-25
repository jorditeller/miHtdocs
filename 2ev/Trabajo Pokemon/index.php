<?php
include('accesoAPI.php');

$region = $_GET['region'] ?? null;
$pokemons = [];

if ($region) {
	switch ($region) {
		case 'kanto':
			$pokemons = Kanto();
			break;
		case 'johto':
			$pokemons = Johto();
			break;
		case 'hoenn':
			$pokemons = Hoenn();
			break;
		case 'sinnoh':
			$pokemons = Sinnoh();
			break;
		case 'unova':
			$pokemons = Unova();
			break;
		case 'kalos':
			$pokemons = Kalos();
			break;
		case 'alola':
			$pokemons = Alola();
			break;
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pokemon</title>
	<link rel="stylesheet" type="text/css" href="examen.css">
</head>

<body>

	<header> Mi blog de &nbsp;&nbsp; <img src="img/International_Pokémon_logo.svg.png"></header>

	<div></div>

	<nav>
		<h2 class="titles">
			<?php if ($region): ?>
				Región de <?= ucfirst($region) ?>
			<?php else: ?>
				Selecciona una región...
			<?php endif; ?>
		</h2>
		<strong>
			<a href="?region=kanto">G1 Kanto</a> &nbsp;&nbsp;
			<a href="?region=johto">G2 Johto</a> &nbsp;&nbsp;
			<a href="?region=hoenn">G3 Hoenn</a> &nbsp;&nbsp;
			<a href="?region=sinnoh">G4 Sinnoh</a> &nbsp;&nbsp;
			<a href="?region=unova">G5 Unova</a> &nbsp;&nbsp;
			<a href="?region=kalos">G6 Kalos</a> &nbsp;&nbsp;
			<a href="?region=alola">G7 Alola</a>
		</strong>
	</nav>



	<div id="iniciales">
		<?php
		if ($pokemons && isset($pokemons['pokemon_entries'])) {
			echo "<ul class='columnas'>";
			foreach ($pokemons['pokemon_entries'] as $pokemon) {
				$entry = $pokemon['entry_number'] ?? null;
				$name = $pokemon['pokemon_species']['name'] ?? null;

				if ($entry !== null && $name !== null) {
					$safeText = htmlspecialchars($entry . " - " . ucfirst($name), ENT_QUOTES, 'UTF-8');
					$href = "pokemon.php?name=" . urlencode($name);

					echo "<li class='txt'><a href='{$href}'>{$safeText}</a></li>";
				}
			}
			echo "</ul>";
		}
		?>

	</div>


	<div class="abajo"></div>

	<footer> Trabajo &nbsp;<strong> Desarrollo Web en Entorno Servidor </strong>&nbsp; 2025/2026 IES Serra Perenxisa.
	</footer>

</body>

</html>