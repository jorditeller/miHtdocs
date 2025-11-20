<?php
require_once 'Database.php';

/**
 * Clase Cancion
 * Maneja todas las operaciones relacionadas con canciones:
 * - Listar canciones de un álbum
 * - Agregar nueva canción
 * - Buscar canciones
 */
class Cancion {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Obtener todas las canciones de un álbum específico
     */
    public function obtenerPorAlbum($codigoAlbum) {
        try {
            $consulta = $this->conn->prepare(
                'SELECT titulo, album FROM cancion WHERE album = ?'
            );
            $consulta->execute([$codigoAlbum]);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener canciones: " . $e->getMessage());
        }
    }
    
    /**
     * Agregar nueva canción a un álbum
     */
    public function agregar($datos) {
        try {
            // Formatear duración
            $minutos = str_pad($datos['minutos'], 2, "0", STR_PAD_LEFT);
            $segundos = str_pad($datos['segundos'], 2, "0", STR_PAD_LEFT);
            $duracion = "00:" . $minutos . ":" . $segundos;
            
            $consulta = $this->conn->prepare(
                'INSERT INTO cancion (titulo, album, duracion, genero) VALUES (?, ?, ?, ?)'
            );
            $consulta->execute([
                $datos['titulo'],
                $datos['cod'],
                $duracion,
                $datos['genero']
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("No se puede introducir ese elemento: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar canciones y/o álbumes según criterios
     * $buscarEn puede ser: 'titulo', 'album', 'ambos'
     */
    public function buscar($texto, $buscarEn, $genero = null) {
        try {
            $parametro = "%" . $texto . "%";
            
            // Construir consulta según el tipo de búsqueda
            if ($buscarEn === "titulo") {
                $sql = "SELECT titulo FROM cancion WHERE titulo LIKE :busqueda";
            } elseif ($buscarEn === "album") {
                $sql = "SELECT titulo FROM album WHERE titulo LIKE :busqueda";
            } else { // ambos
                $sql = "SELECT titulo FROM cancion WHERE titulo LIKE :busqueda
                        UNION
                        SELECT titulo FROM album WHERE titulo LIKE :busqueda";
            }
            
            $consulta = $this->conn->prepare($sql);
            $consulta->bindParam(':busqueda', $parametro, PDO::PARAM_STR);
            
            // TODO: Implementar filtro por género cuando se solucione el problema del select
            // if ($genero) {
            //     $consulta->bindParam(':genero', $genero, PDO::PARAM_STR);
            // }
            
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new Exception("Error en la búsqueda: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener lista de géneros disponibles
     */
    public static function getGenerosDisponibles() {
        return [
            'acustica' => 'Acústica',
            'bso' => 'BSO',
            'blues' => 'Blues',
            'folk' => 'Folk',
            'jazz' => 'Jazz',
            'new_age' => 'New age',
            'pop' => 'Pop',
            'rock' => 'Rock',
            'electronica' => 'Electrónica'
        ];
    }
}