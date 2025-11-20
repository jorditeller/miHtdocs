<?php
require_once 'Database.php';

/**
 * Clase Album
 * Maneja todas las operaciones CRUD de álbumes:
 * - Listar todos los álbumes
 * - Crear nuevo álbum
 * - Eliminar álbum
 * - Obtener información de un álbum específico
 */
class Album {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Obtener todos los álbumes
     * Retorna array con todos los álbumes
     */
    public function obtenerTodos() {
        try {
            $resultado = $this->conn->query('SELECT titulo, codigo FROM album');
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener álbumes: " . $e->getMessage());
        }
    }
    
    /**
     * Crear nuevo álbum
     */
    public function crear($titulo) {
        try {
            $consulta = $this->conn->prepare('INSERT INTO album (titulo) VALUES (?)');
            $consulta->execute([$titulo]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("No se puede añadir ese elemento: " . $e->getMessage());
        }
    }
    
    /**
     * Eliminar álbum por código
     */
    public function eliminar($codigo) {
        try {
            $consulta = $this->conn->prepare('DELETE FROM album WHERE codigo = ?');
            $consulta->execute([$codigo]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("No se puede eliminar ese elemento: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener información de un álbum específico
     */
    public function obtenerPorCodigo($codigo) {
        try {
            $consulta = $this->conn->prepare('SELECT titulo, codigo FROM album WHERE codigo = ?');
            $consulta->execute([$codigo]);
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener álbum: " . $e->getMessage());
        }
    }
}