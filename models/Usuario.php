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
        $email = $data['email'];
        $password = $data['password'];
        $sql = "SELECT id, password, is_verified FROM user_auth WHERE email = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                if ($row['is_verified']) {
                    $session_id = session_id();
                    $sqlSession = "INSERT INTO user_sessions (user_auth_id, session_id, created_at, updated_at) VALUES (" . $row['id'] . ", '$session_id', NOW(), NOW())";
                    if ($this->conn->query($sqlSession) === TRUE) {
                        jsonResponse(["success" => true, "message" => "Inicio de sesión exitoso", "session_id" => $session_id]);
                    } else {
                        jsonResponse(["success" => false, "message" => "Error al iniciar sesión"], 500);
                    }
                } else {
                    jsonResponse(["success" => false, "message" => "Cuenta no verificada. Por favor verifica tu correo."], 400);
                }
            } else {
                jsonResponse(["success" => false, "message" => "Contraseña incorrecta."], 400);
            }
        } else {
            jsonResponse(["success" => false, "message" => "Correo electrónico no registrado."], 400);
        }
    }

    public function verify($data)
    {
        // Implementar la función verify
    }
}