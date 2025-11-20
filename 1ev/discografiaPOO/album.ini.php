<?php
    class Album{
        private $cod;
        private $titulo;
        private $dicografia;
        private $formato;
        private $fechaL;
        private $fechaC;
        private $precio;

        public function __construct($cod,$titulo,$dicografia,$formato,$fechaL,$fechaC,$precio){
            $this->cod = $cod;
            $this->titulo = $titulo;
            $this->dicografia = $dicografia;
            $this->formato = $formato;
            $this->fechaL = $fechaL;
            $this->fechaC = $fechaC;
            $this->precio = $precio;
        }

        public function getCod(){
            return $this->cod;
        }
        public function setCod($cod){
            $this->cod = $cod;
        }

        public function getTitulo(){
            return $this->titulo;
        }
        public function setTitulo($titulo){
            $this->titulo= $titulo;
        }

        public function getDiscografia(){
            return $this->dicografia;
        }
        public function setDiscografia($dicografia){
            $this->dicografia = $dicografia;
        }

        public function getFormato(){
            return $this->formato;
        }
        public function setFormato($formato){
            $this->formato = $formato;
        }

        public function getFechaL(){
            return $this->fechaL;
        }
        public function setFechaL($fechaL){
            $this->fechaL = $fechaL;
        }

        public function getFechaC(){
            return $this->fechaC;
        }
        public function setFechaC($fechaC){
            $this->fechaC = $fechaC;
        }

        public function getPrecio(){
            return $this->precio;
        }
        public function setPrecio($precio){
            $this->precio = $precio;
        }

        public function registrarDisco($conexion){
            try{
            $consulta = $conexion->exec('INSERT INTO discografia.album (titulo,discografia,formato,fechaLanzamiento,fechaCompra,precio) VALUES ("'. $this->getTitulo() .'","'. $this->getDiscografia() .'","'.$this->getFormato().'","'. $this->getFechaL() .'","'.$this->getFechaC().'",'.$this->precio.');');
            echo'<h1 id="bien">DISCO '.$this->titulo.' REGISTRADO!</h1>';
            }catch(Exception $e){
                echo '<h1 id="mal">ERROR AL INSERTAR EL DISCO!</h1>';
                echo $e;
            }
        }

        function borrarDisco($conexion,$tc){
            try{
                $ok = true;
                if($tc != 0){
                    $consulta1 = 'DELETE from discografia.cancion where album = '.$this->cod.';';
                    $conexion->beginTransaction(); // Devuelve true o false, si cambia o no el modo
                    if($conexion->exec($consulta1) == 0){
                        $ok = false;
                    }
                    $consulta2 = 'DELETE from discografia.album where cod = '.$this->cod.';';
                    if($conexion->exec($consulta2) == 0){
                        $ok = false;
                    }
                    if ($ok){
                        $conexion->commit(); // Si todo fue bien, confirma los cambios
                        echo'<h1 id="bien">SE BORRÓ EL DISCO CON ÉXITO!</h1>';
                    }
                    else{
                        $conexion->rollback(); // y si no, los revierte
                        echo '<h1 id="mal">ERROR AL BORRAR EL DISCO!</h1>';
                    }
                }else{
                    $consulta = $conexion->exec('DELETE from discografia.album where cod = '.$this->cod.';');
                    echo'<h1 id="bien">SE BORRÓ EL DISCO CON ÉXITO!</h1>';
                }
            }catch(Exception $e){
                echo '<h1 id="mal">ERROR AL BORRAR EL DISCO!</h1>';
                echo $e;
            }
        }
    }
?>