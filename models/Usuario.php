<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../views/response.php';


class Usuario
{
    private $conn;
    private $authController;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->authController = new \AuthController();
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
                // Obtener el ID del rol 'user'
                $sqlRol = "SELECT id FROM user_roles WHERE name = 'user'";
                $resultRol = $this->conn->query($sqlRol);
                if ($resultRol->num_rows > 0) {
                    $rowRol = $resultRol->fetch_assoc();
                    $userRolId = $rowRol['id'];

                    // Se le asigna el rol al usuario recien registrado
                    $sqlAssignRol = "INSERT INTO user_role_assignments (user_auth_id, role_id) VALUES ($user_auth_id, $userRolId)";
                    $this->conn->query($sqlAssignRol);
                }
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
                    // Guardar el id del usuario en la sesión
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['is_authenticated'] = true;

                    // Traerme el rol del usuario para validar si tiene permisos
                    $sqlRoles = "SELECT role_id FROM user_role_assignments WHERE user_auth_id = " . $row['id'];
                    $resultRoles = $this->conn->query($sqlRoles);
                    if ($resultRoles->num_rows > 0) {
                        $rowRoles = $resultRoles->fetch_assoc();
                        $_SESSION['role_id'] = $rowRoles['role_id'];
                    }

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

    public function logout()
    {
        // Comprobar si una sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Destruir la sesión y limpiar la información de sesión
        session_unset();
        session_destroy();

        return ["success" => true, "message" => "Desconectado exitosamente."];
    }


    public function verify($data)
    {
        // Implementar la función verify
        $email = $data['email'];
        $token = $data['token'];
        $sql = "SELECT id FROM user_auth WHERE email = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_auth_id = $row['id'];
            if ($this->authController->verifyToken($user_auth_id, $token)) {
                $sqlUpdate = "UPDATE user_auth SET is_verified = TRUE WHERE id = $user_auth_id";
                if ($this->conn->query($sqlUpdate)) {
                    jsonResponse(["success" => true, "message" => "Verificación exitosa."]);
                } else {
                    jsonResponse(["success" => false, "message" => "Error al verificar la cuenta."], 500);
                }
            } else {
                jsonResponse(["success" => false, "message" => "Token incorrecto o expirado."], 400);
            }
        } else {
            jsonResponse(["success" => false, "message" => "Correo electrónico no registrado."], 400);
        }
    }
}
