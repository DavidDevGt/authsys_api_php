<?php
require_once '../config/db.php';
require_once '../controllers/AuthController.php';
require_once '../views/response.php';

class Usuario
{
    private $conn;
    private $authController;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->authController = new AuthController();
    }

    public function register($data)
    {
        // Implementar la función register
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user_auth (email, password) VALUES ('$email', '$password')";

        if ($this->conn->query($sql) === TRUE) {
            $user_auth_id = $this->conn->insert_id;
            $token = $this->authController->generarToken($user_auth_id);
            if ($token) {
                mail($email, "Verificación de cuenta", "Tu código de verificación es: $token. Tienes 30 minutos para verificar tu cuenta.");
                jsonResponse(["success" => true, "message" => "Registro exitoso. Verifica tu correo para activar la cuenta."]);
            } else {
                jsonResponse(["success" => false, "message" => "Error al generar el token"], 500);
            }
        } else {
            jsonResponse(["success" => false, "message" => "Error al registrar el usuario"], 500);
        }
    }

    public function login($data)
    {
        // Implementar la función login
    }

    public function verify($data)
    {
        // Implementar la función verify
    }
}