<?php

require_once '../config/db.php';

class AuthController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generarToken($user_auth_id)
    {
        // Generar un token aleatorio
        $token = substr(md5(uniqid(rand(), true)), 0, 5);

        // Insertar el token en la base de datos
        $sql = "INSERT INTO verification_tokens (user_auth_id, token, created_at) VALUES ($user_auth_id, '$token', NOW())";
        if ($this->conn->query($sql) === TRUE) {
            return $token;
        } else {
            return false;
        }
    }

    public function verifyToken($user_auth_id, $token)
    {
        // Verificar si el token es válido
        $sql = "SELECT token FROM verification_tokens WHERE user_auth_id = $user_auth_id AND token = '$token'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            // Eliminar tokens antiguos ya usados de este usuario
            $sqlDelete = "DELETE FROM verification_tokens WHERE user_auth_id = $user_auth_id";
            $this->conn->query($sqlDelete);
            return true;
        } else {
            return false;
        }
    }

    public function deleteOldToken()
    {
        // Eliminar tokens que tengan más de 30 minutos de haber sido creados
        $sql = "DELETE FROM verification_tokens WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
        $this->conn->query($sql);
    }
}