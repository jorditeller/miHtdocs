<?php
    $jsonString = file_get_contents('./marvel_characters.json');
    $arrayJSON = json_decode($jsonString, true);

    echo "<h1>Personajes de Marvel</h1>";

    echo $arrayJSON[0]['name'];
    echo $arrayJSON[0]['comics']['items'];

     foreach ($arrayJSON as $personaje) {
        echo $personaje['name'] . "<br>";
    }

    foreach ($arrayJSON[0]['comics']['items'] as $comic) {
        echo $comic['name'] . "<br>";
    }   
?>

<table>

</table>