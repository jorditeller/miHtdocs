<?php
require_once 'Database.php';

/**
 * Clase Usuario
 * Maneja todas las operaciones relacionadas con usuarios:
 * - Autenticación (login)
 * - Registro
 * - Gestión de cookies
 * - Manejo de imágenes de perfil
 */
class Usuario {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Verificar si existe una cookie de usuario
     */
    public function tieneCookie() {
        return isset($_COOKIE["user"]);
    }
    
    /**
     * Obtener el nombre de usuario de la cookie
     */
    public function getUsuarioCookie() {
        return $_COOKIE["user"] ?? null;
    }
    
    /**
     * Establecer cookie de usuario por 30 días
     */
    public function setCookie($usuario) {
        setcookie("user", $usuario, time() + (86400 * 30));
    }
    
    /**
     * Eliminar cookie de usuario
     */
    public function eliminarCookie() {
        setcookie("user", "", time() - 3600);
    }
    
    /**
     * Autenticar usuario con nombre de usuario y contraseña
     * Retorna true si las credenciales son válidas
     */
    public function login($usuario, $password) {
        try {
            $consulta = $this->conn->prepare(
                "SELECT id, usuario, password FROM tabla_usuarios WHERE usuario = :user"
            );
            $consulta->bindParam(':user', $usuario);
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                $fila = $consulta->fetch(PDO::FETCH_ASSOC);
                $hash = $fila["password"];
                
                // Verificar contraseña encriptada
                if (password_verify($password, $hash)) {
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error en login: " . $e->getMessage());
        }
    }
    
    /**
     * Registrar nuevo usuario
     * Incluye validación y procesamiento de imagen
     */
    public function registrar($datos, $archivo) {
        $usuario = $datos['usuario'] ?? null;
        $password = $datos['password'] ?? null;
        
        if (!$usuario || !$password) {
            throw new Exception("Usuario y contraseña son obligatorios");
        }
        
        // Procesar imagen de perfil
        $rutas = $this->procesarImagen($archivo, $usuario);
        
        // Hashear contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $consulta = $this->conn->prepare(
                'INSERT INTO tabla_usuarios (usuario, password, rutaImg, rutaImgGrande) 
                 VALUES (?, ?, ?, ?)'
            );
            $consulta->execute([
                $usuario,
                $hash,
                $rutas['mini'],
                $rutas['grande']
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al registrar usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Procesar y guardar imagen de perfil
     * Crea versión grande y miniatura
     */
    private function procesarImagen($archivo, $usuario) {
        // Validar subida
        switch ($archivo['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception("No se ha enviado ningún archivo.");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception("El archivo supera el tamaño máximo permitido.");
            default:
                throw new Exception("Error desconocido al subir el archivo.");
        }
        
        $tipo = $archivo['type'];
        $tmp_name = $archivo['tmp_name'];
        $nombreArchivo = basename($archivo['name']);
        
        // Validar tipo de imagen
        if ($tipo !== "image/png" && $tipo !== "image/jpeg") {
            throw new Exception("Error: Solo se permiten imágenes PNG o JPG.");
        }
        
        // Validar dimensiones mínimas
        $size = getimagesize($tmp_name);
        $ancho = $size[0];
        $alto = $size[1];
        
        if ($ancho < 360 || $alto < 480) {
            throw new Exception("Error: La imagen debe tener al menos 360x480 píxeles.");
        }
        
        // Crear carpetas
        $carpetaBase = __DIR__ . "/img/users/$usuario";
        $carpetaGrande = "$carpetaBase/grande";
        $carpetaMini = "$carpetaBase/mini";
        
        if (!file_exists($carpetaGrande)) mkdir($carpetaGrande, 0777, true);
        if (!file_exists($carpetaMini)) mkdir($carpetaMini, 0777, true);
        
        // Guardar imagen grande
        $rutaFisicaGrande = "$carpetaGrande/$nombreArchivo";
        $rutaWebGrande = "img/users/$usuario/grande/$nombreArchivo";
        
        move_uploaded_file($tmp_name, $rutaFisicaGrande);
        
        // Crear miniatura
        if ($tipo === "image/png") {
            $imagen = imagecreatefrompng($rutaFisicaGrande);
        } else {
            $imagen = imagecreatefromjpeg($rutaFisicaGrande);
        }
        
        $imagenReducida = imagescale($imagen, 150, 200);
        $rutaFisicaMini = "$carpetaMini/$nombreArchivo";
        $rutaWebMini = "img/users/$usuario/mini/$nombreArchivo";
        
        if ($tipo === "image/png") {
            imagepng($imagenReducida, $rutaFisicaMini);
        } else {
            imagejpeg($imagenReducida, $rutaFisicaMini, 90);
        }
        
        imagedestroy($imagen);
        imagedestroy($imagenReducida);
        
        return [
            'mini' => $rutaWebMini,
            'grande' => $rutaWebGrande
        ];
    }
    
    /**
     * Obtener rutas de imágenes del usuario
     */
    public function getRutasImagenes($usuario) {
        try {
            $consulta = $this->conn->prepare(
                "SELECT rutaImg, rutaImgGrande FROM tabla_usuarios WHERE usuario = ?"
            );
            $consulta->execute([$usuario]);
            $fila = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return [
                'mini' => $fila["rutaImg"] ?? 'img/default-mini.jpg',
                'grande' => $fila["rutaImgGrande"] ?? 'img/default-grande.jpg'
            ];
        } catch (PDOException $e) {
            throw new Exception("Error al obtener imágenes: " . $e->getMessage());
        }
    }
}