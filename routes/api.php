<?php
// Encabezados para soporte CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/RolController.php';
require_once __DIR__ . '/../controllers/PermisoController.php';
require_once __DIR__ . '/../views/response.php';

$usuarioController = new UsuarioController();
$rolController = new RolController();
$permisoController = new PermisoController();

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
                case 'addRole':
                    // Agregar un rol
                    $response = $rolController->addRole($_POST);
                    jsonResponse($response);
                    break;
                case 'updateRole':
                    // Actualizar un rol
                    $response = $rolController->updateRole($_POST['id'], $_POST);
                    break;
                case 'deleteRole':
                    // Eliminar un rol
                    $response = $rolController->deleteRole($_POST['id']);
                    jsonResponse($response);
                    break;
                case 'addPermission':
                    // Agregar un permiso
                    $response = $permisoController->addPermission($_POST);
                    jsonResponse($response);
                    break;
                case 'updatePermission':
                    // Actualizar un permiso
                    $response = $permisoController->updatePermission($_POST['id'], $_POST);
                    jsonResponse($response);
                    break;
                case 'deletePermission':
                    // Eliminar un permiso
                    $response = $permisoController->deletePermission($_POST['id']);
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

    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getAllRoles':
                    // Devolver todos los roles
                    $response = $rolController->getAllRoles();
                    jsonResponse($response);
                    break;
                case 'getRoleByName':
                    // Devolver el rol con el nombre determinado
                    $response = $rolController->getRoleByName($_GET['name']);
                    break;
                case 'getAllPermissions':
                    // Devolver todos los permisos
                    $response = $permisoController->getAllPermissions();
                    jsonResponse($response);
                    break;
                case 'getPermissionByName':
                    // Devolver el permiso con el nombre determinado
                    $response = $permisoController->getPermissionByName($_GET['name']);
                    jsonResponse($response);
                    break;
                default:
                    // Acción no reconocida
                    jsonResponse(["message" => "Acción no permitida"], 400);
                    break;
            }
        } else {
            jsonResponse(["message" => "Acción no especificada"], 400);
        }
        break;
    default:
        // Método HTTP no permitido
        jsonResponse(["message" => "Método no permitido"], 405);
        break;
}
