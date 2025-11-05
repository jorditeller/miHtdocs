<?php
    class Cancion{
        private $titulo;
        private $album;
        private $posicion;
        private $duracion;
        private $genero;

        public function __construct($titulo,$album,$posicion,$duracion,$genero){
            $this->titulo = $titulo;
            $this->album = $album;
            $this->posicion = $posicion;
            $this->duracion = $duracion;
            $this->genero = $genero;
        }

        public function getTitulo(){
            return $this->titulo;
        }
        public function setTitulo($titulo){
            $this->titulo= $titulo;
        }

        public function getAlbum(){
            return $this->album;
        }
        public function setAlbum($album){
            $this->album = $album;
        }

        public function getPosicion(){
            return $this->posicion;
        }
        public function setPosicion($posicion){
            $this->posicion = $posicion;
        }

        public function getDuracion(){
            return $this->duracion;
        }
        public function setDuracion($duracion){
            $this->duracion = $duracion;
        }

        public function getGenero(){
            return $this->genero;
        }
        public function setGenero($genero){
            $this->genero = $genero;
        }

        function registrarCancion($conexion){
            try{
            $consulta = $conexion->exec('INSERT INTO discografia.cancion (titulo,album,posicion,duracion,genero) VALUES ("'. $this->titulo .'",'. $this->album .','.$this->posicion.',"'. $this->duracion .'","'.$this->genero.'");');
            echo'<h1 id="bien">Canción '.$this->titulo.' REGISTRADA!</h1>';
            }catch(Exception $e){
                echo '<h1 id="mal">ERROR AL INSERTAR LA CANCIÓN!</h1>';
                echo $e;
            }
        }
    }
?>