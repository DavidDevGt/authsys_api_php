<?php
require_once '../controllers/UsuarioController.php';
require_once '../views/response.php';

// Registro de usuario
$usuarioController = new UsuarioController();

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        if ($_GET['action'] == 'register') {
            $response = $usuarioController->register($_POST);
            jsonResponse($response);
        } elseif ($_GET['action'] == 'login') {
            $response = $usuarioController->login($_POST);
            jsonResponse($response);
        } elseif ($_GET['action'] == 'verify') {
            $response = $usuarioController->verify($_POST);
            jsonResponse($response);
        }
        break;

    default:
        jsonResponse(["message" => "Método no permitido"], 405);
        break;
}