<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte</title>
</head>

<body>
    <?php
    class Soporte
    {
        private $titulo;
        private $numero;
        private $precio;
        private const VAT = 0.21;

        public function __construct($titulo, $numero, $precio)
        {
            $this->titulo = $titulo;
            $this->numero = $numero;
            $this->precio = $precio;
        }

        public function __set($propiedad, $valor)
        {
            $this->$propiedad = $valor;
        }

        public function __get($propiedad)
        {
            return $this->$propiedad;
        }

        public function getPrecioConIVA()
        {
            return $this->precio * (1 + self::VAT);
        }

        public function muestraResumen()
        {
            echo "<p>" . $this->titulo . "<br>" . $this->precio . " € (IVA no incluido)</p>";
        }
    }

    $soporte1 = new Soporte("Tenet", 22, 3);
    echo "<strong>" . $soporte1->titulo . "</strong>";
    echo "<br>Precio: " . $soporte1->precio . " euros";
    echo "<br>Precio IVA incluido: " . $soporte1->getPrecioConIVA() . " euros";
    $soporte1->muestraResumen();

    class CintaVideo extends Soporte
    { 
        private $duracion;

        public function __construct($titulo, $numero, $precio, $duracion)
        {
            parent::__construct($titulo, $numero, $precio);
            $this->duracion = $duracion;
        }

        public function muestraResumen()
        {
            echo "<p>Película en VHS:<br>" . $this->titulo . "<br>" . $this->precio . " € (IVA no incluido)<br>Duración: " . $this->duracion . " minutos</p>";
        }
    }

    $miCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 107);
    echo "<strong>" . $miCinta->titulo . "</strong>";
    echo "<br>Precio: " . $miCinta->precio . " euros";
    echo "<br>Precio IVA incluido: " . $miCinta->getPrecioConIVA() . " euros";
    $miCinta->muestraResumen();

    class Dvd extends Soporte
    {
        private $idiomas;
        private $formatoPantalla;

        public function __construct($titulo, $numero, $precio, $idiomas, $formatoPantalla)
        {
            parent::__construct($titulo, $numero, $precio);
            $this->idiomas = $idiomas;
            $this->formatoPantalla = $formatoPantalla;
        }

        public function muestraResumen()
        {
            echo "<p>Película en DVD:<br>" . $this->titulo . "<br>" . $this->precio . " € (IVA no incluido)<br>Idiomas: " . $this->idiomas . "<br>Formato pantalla: " . $this->formatoPantalla . "</p>";
        }
    }

    $miDvd = new Dvd("Origen", 24, 15, "es,en,fr", "16:9");
    echo "<strong>" . $miDvd->titulo . "</strong>";
    echo "<br>Precio: " . $miDvd->precio . " euros";
    echo "<br>Precio IVA incluido: " . $miDvd->getPrecioConIva() . " euros";
    $miDvd->muestraResumen();

    class Juego extends Soporte
    {
        private $consola;
        private $minNumJugadores;
        private $maxMinJugadores;

        public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxMinJugadores)
        {
            parent::__construct($titulo, $numero, $precio);
            $this->consola = $consola;
            $this->minNumJugadores = $minNumJugadores;
            $this->maxMinJugadores = $maxMinJugadores;
        }

        public function muestraJugadoresPosibles()
        {
            if ($this->minNumJugadores === 1 && $this->maxMinJugadores === 1) {
                echo "Para un jugador";
            } else {
                echo "De {$this->minNumJugadores} a {$this->maxMinJugadores} jugadores";
            }
        }

        public function muestraResumen()
        {
            echo "<p>Juego para: " . $this->consola . "<br>" . $this->titulo . "<br>" . $this->precio . " € (IVA no incluido)<br>";
            $this->muestraJugadoresPosibles();
            echo "</p>";
        }
    }

    $miJuego = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1, 1);
    echo "<strong>" . $miJuego->titulo . "</strong>";
    echo "<br>Precio: " . $miJuego->precio . " euros";
    echo "<br>Precio IVA incluido: " . $miJuego->getPrecioConIva() . " euros";
    $miJuego->muestraResumen();

    ?>
</body>

</html>