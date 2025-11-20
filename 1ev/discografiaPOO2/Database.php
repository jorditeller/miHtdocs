<?php
/**
 * Clase Database
 * Maneja la conexión a la base de datos usando el patrón Singleton
 * para asegurar una única instancia de conexión
 */
class Database {
    private static $instance = null;
    private $conn;
    
    // Constructor privado para evitar instancias múltiples
    private function __construct() {
        try {
            $this->conn = new PDO(
                'mysql:host=localhost;dbname=discografia;charset=utf8mb4',
                'root',
                ''
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Falló la conexión: ' . $e->getMessage());
        }
    }
    
    // Método estático para obtener la única instancia de la clase
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    // Obtener la conexión PDO
    public function getConnection() {
        return $this->conn;
    }
    
    // Prevenir clonación del objeto
    private function __clone() {}
    
    // Prevenir deserialización del objeto
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}