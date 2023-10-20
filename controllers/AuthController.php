<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../response.php';

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
        // GENERAR EL TOKEN 5 CARACTERES ALEATORIOS
        $token = substr(bin2hex(random_bytes(3)), 0, 5);

        // GUARDAR EL TOKEN EN LA BASE DE DATOS
        $sql = "INSERT INTO verification_tokens (user_auth_id, token, created_at) VALUES ($user_auth_id, '$token', NOW())";
        if ($this->conn->query($sql) === TRUE) {
            return $token;
        } else {
            jsonResponse(["message" => "Error al generar el token. Por favor, intente nuevamente."], 500);
            exit;
        }
    }

    public function verifyToken($user_auth_id, $token)
    {
        // VERIFICAR QUE EL TOKEN SEA VÁLIDO
        $sql = "SELECT token FROM verification_tokens WHERE user_auth_id = $user_auth_id AND token = '$token'";
        $result = $this->conn->query($sql);
        if (!$result) {
            // SI HAY ERROR AL EJECUTAR LA CONSULTA
            jsonResponse(["message" => "Error al verificar el token. Por favor, intente nuevamente."], 500);
            exit;
        }
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // ESTO ES PARA CALCULAR LOS TIEMPOS DE LOS TOKENS CREADOS ANTERIORMENTE
            $tokenCreatedAt = new DateTime($row['created_at']);
            $currentTime = new DateTime();

            $interval = $currentTime->diff($tokenCreatedAt);
            $minutesPassed = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

            // SI EL TOKEN SE CREO HACE MAS DE 30 MIN SE BORRA DE LA BASE DE DATOS
            if ($minutesPassed <= 30) {
                $sqlDelete = "DELETE FROM verification_tokens WHERE user_auth_id = $user_auth_id";
                if ($this->conn->query($sqlDelete) === FALSE) {
                    jsonResponse(["message" => "Error al eliminar el token antiguo. Por favor, intente nuevamente."], 500);
                    exit;
                }
                return true;
            }
        }
        return false;
    }

    // ESTA FUNCIÓN ES PARA BORRAR TODOS LOS TOKENS QUE SE CREARON HACE MAS DE 30 MIN EN LA BASE DE DATOS
    public function deleteOldToken()
    {
        $sql = "DELETE FROM verification_tokens WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
        if ($this->conn->query($sql) === FALSE) {
            jsonResponse(["message" => "Error al eliminar tokens antiguos. Por favor, intente nuevamente."], 500);
            exit;
        }
    }
}
