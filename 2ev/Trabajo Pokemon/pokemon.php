<?php
$name = $_GET['name'] ?? null;

if (!$name) {
    echo "Falta el parámetro 'name'.";
    exit;
}

$ch = curl_init();
$url = "https://pokeapi.co/api/v2/pokemon/" . urlencode($name);
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!$data) {
    echo "No se pudo obtener información del Pokémon.";
    exit;
}

$height = $data['height'] ?? '';
$weight = $data['weight'] ?? '';
$types  = array_map(fn($t) => ucfirst($t['type']['name']), $data['types'] ?? []);
$abilities = array_map(fn($a) => ucfirst($a['ability']['name']), $data['abilities'] ?? []);
$image = $data['sprites']['other']['official-artwork']['front_default'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= ucfirst($name) ?> - Pokémon</title>
    <link rel="stylesheet" type="text/css" href="examen.css">
</head>
<body>

<header> Mi blog de &nbsp;&nbsp; <img src="img/International_Pokémon_logo.svg.png"></header>

<div></div>

<article>
    <h1 class="titles"><?= htmlspecialchars(ucfirst($name), ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if ($image): ?>
        <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Imagen de <?= ucfirst($name) ?>">
    <?php endif; ?>

    <p><strong>Altura:</strong> <?= $height ?>cm</p>
    <p><strong>Peso:</strong> <?= $weight ?>kg</p>
    <p><strong>Tipos:</strong> <?= htmlspecialchars(implode(', ', $types), ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Habilidades:</strong> <?= htmlspecialchars(implode(', ', $abilities), ENT_QUOTES, 'UTF-8') ?></p>
</article>

<footer> Trabajo &nbsp;<strong> Desarrollo Web en Entorno Servidor </strong>&nbsp; 2025/2026 IES Serra Perenxisa.</footer>

</body>
</html>