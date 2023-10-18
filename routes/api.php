<?php
// Encabezados para soporte CORS
header("Access-Control-Allow-Origin: *"); // Aqui tengo que cambiar * por mi sitio web cuando mande a produccion
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE"); // Metodos que permito en mi API
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Headers

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Archivos requeridos
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/RolController.php';
require_once __DIR__ . '/../controllers/PermisoController.php';
require_once __DIR__ . '/../views/response.php';

// Declaramos los controladores
$usuarioController = new UsuarioController();
$rolController = new RolController();
$permisoController = new PermisoController();

// Verificar el método de la solicitud HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Funciones para verificar la sesión del usuario
function verificarSesion()
{
    if (!isset($_SESSION['user_id']) || !$_SESSION['is_authenticated']) {
        jsonResponse(["message" => "No estás autenticado. Por favor, inicia sesión."], 401);
        exit;
    }
    return true;
}

// Procesar la solicitud según el método HTTP y la acción proporcionada
switch ($requestMethod) {
        // Peticiones POST
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
                case 'logout':
                    verificarSesion();
                    $response = $usuarioController->logout();
                    jsonResponse($response);
                    break;
                case 'addRole':
                    // Agregar un rol
                    verificarSesion();
                    $response = $rolController->addRole($_POST);
                    jsonResponse($response);
                    break;
                case 'updateRole':
                    // Actualizar un rol
                    verificarSesion();
                    $response = $rolController->updateRole($_POST['id'], $_POST);
                    break;
                case 'deleteRole':
                    // Eliminar un rol
                    verificarSesion();
                    $response = $rolController->deleteRole($_POST['id']);
                    jsonResponse($response);
                    break;
                case 'addPermission':
                    // Agregar un permiso
                    verificarSesion();
                    $response = $permisoController->addPermission($_POST);
                    jsonResponse($response);
                    break;
                case 'updatePermission':
                    // Actualizar un permiso
                    verificarSesion();
                    $response = $permisoController->updatePermission($_POST['id'], $_POST);
                    jsonResponse($response);
                    break;
                case 'deletePermission':
                    // Eliminar un permiso
                    verificarSesion();
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

        // Peticiones GET
    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getAllRoles':
                    // Devolver todos los roles
                    verificarSesion();
                    $response = $rolController->getAllRoles();
                    jsonResponse($response);
                    break;
                case 'getRoleById':
                    // Devolver un rol por su id
                    verificarSesion();
                    if (!isset($_GET['id'])) {
                        jsonResponse(["message" => "El parámetro 'id' es requerido"], 400);
                        exit;
                    }
                    $response = $rolController->getRoleById($_GET['id']);
                    jsonResponse($response);
                    break;
                case 'getAllPermissions':
                    // Devolver todos los permisos
                    verificarSesion();
                    $response = $permisoController->getAllPermissions();
                    jsonResponse($response);
                    break;
                case 'getPermissionById':
                    // Devolver un permiso por su id
                    verificarSesion();
                    if (!isset($_GET['id'])) {
                        jsonResponse(["message" => "El parámetro 'id' es requerido"], 400);
                        exit;
                    }
                    $response = $permisoController->getPermissionById($_GET['id']);
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
