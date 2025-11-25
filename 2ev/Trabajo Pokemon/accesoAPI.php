<?php
function getRegion($id)
{
	$ch = curl_init();
	$url = "https://pokeapi.co/api/v2/pokedex/$id/";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json_data = curl_exec($ch);
	curl_close($ch);

	return json_decode($json_data, true);
}

function Kanto()
{
	return getRegion(id: 2);
}
function Johto()
{
	return getRegion(3);
}
function Hoenn()
{
	return getRegion(4);
}
function Sinnoh()
{
	return getRegion(5);
}
function Unova()
{
	return getRegion(8);
}
function Kalos()
{
	return getRegion(12);
}
function Alola()
{
	return getRegion(16);
}



function getPokemon($id)
{
	$ch = curl_init();
	$url = "https://pokeapi.co/api/v2/pokemon-species/$id/";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json_data = curl_exec($ch);
	curl_close($ch);

	return json_decode($json_data, true);
}
?>