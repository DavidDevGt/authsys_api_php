<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../views/response.php';

$usuarioController = new UsuarioController();

// Verificar el método de la solicitud HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Procesar la solicitud según el método HTTP y la acción proporcionada
switch ($requestMethod) {
    case 'POST':
        // Verificar si se proporciona una acción
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'register':
                    // Registrar un usuario
                    $response = $usuarioController->register($_POST);
                    jsonResponse($response);
                    break;
                case 'login':
                    // Iniciar sesión de un usuario
                    $response = $usuarioController->login($_POST);
                    jsonResponse($response);
                    break;
                case 'verify':
                    // Verificar la cuenta de un usuario
                    $response = $usuarioController->verify($_POST);
                    jsonResponse($response);
                    break;
                default:
                    // Acción no reconocida
                    jsonResponse(["message" => "Acción no permitida"], 400);
                    break;
            }
        } else {
            // No se proporcionó ninguna acción
            jsonResponse(["message" => "Acción no especificada"], 400);
        }
        break;

    default:
        // Método HTTP no permitido
        jsonResponse(["message" => "Método no permitido"], 405);
        break;
}