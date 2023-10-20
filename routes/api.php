<?php
// Archivos requeridos
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/RolController.php';
require_once __DIR__ . '/../controllers/PermisoController.php';
require_once __DIR__ . '/../views/response.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Encabezados para soporte CORS
header("Access-Control-Allow-Origin: *"); // Aqui tengo que cambiar * por mi sitio web cuando mande a produccion
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE"); // Metodos que permito en mi API
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Headers

// Sesiones PHP
session_start();

// Finalizar ejecución en caso de método OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Declaramos los controladores
$usuarioController = new UsuarioController();
$rolController = new RolController();
$permisoController = new PermisoController();

// Verificar el método de la solicitud HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Funciones para la API

/**
 * La función "verificarSesion" verifica si el usuario está autenticado y devuelve verdadero si lo
 * está, de lo contrario envía una respuesta JSON con un mensaje de error y sale del script.
 * 
 * @return - un valor booleano.
 */
function verificarSesion()
{
    if (!isset($_SESSION['user_id']) || !$_SESSION['is_authenticated']) {
        jsonResponse(["message" => "No estás autenticado. Por favor, inicia sesión."], 401);
        exit;
    }
    return true;
}

/**
 * La función "verificarRol" verifica si el usuario tiene el rol requerido para realizar una
 * determinada acción.
 * 
 * @param - rolRequerido El parámetro "rolRequerido" representa el rol requerido por un usuario para
 * realizar una determinada acción.
 */
function verificarRol($rolRequerido)
{
    // Primero, verificamos si el usuario está autenticado
    verificarSesion();

    // Luego, verificamos si tiene el rol requerido
    if ($_SESSION['role_id'] != $rolRequerido) {
        jsonResponse(["message" => "No tienes permiso para realizar esta acción."], 403);
        exit;
    }
}

/**
 * La función "validarParametro" comprueba si falta algún parámetro o tiene un tipo incorrecto y
 * devuelve verdadero si todo es válido.
 * 
 * @param - parametro El parámetro "parámetro" es el valor que necesita ser validado. Puede ser de
 * cualquier tipo de datos.
 * @param - tipoEsperado El parámetro "tipoEsperado" es un parámetro opcional que especifica el tipo de
 * datos esperado del parámetro "parametro". Si se proporciona, la función comprobará si el tipo de
 * datos del parámetro "parámetro" coincide con el tipo esperado.
 * 
 * @return - un valor booleano de verdadero.
 */
function validarParametro($parametro, $tipoEsperado = null) {
    if (!isset($parametro)) {
        jsonResponse(["message" => "Parámetro faltante"], 400);
        exit;
    }
    if ($tipoEsperado && gettype($parametro) !== $tipoEsperado) {
        jsonResponse(["message" => "Tipo de parámetro incorrecto"], 400);
        exit;
    }
    return true;
}

// Procesar la solicitud según el método HTTP y la acción proporcionada
switch ($requestMethod) {
    case 'POST':
        validarParametro($_GET['action'], 'string');
        switch ($_GET['action']) {
            case 'register':
                validarParametro($_POST);
                $response = $usuarioController->register($_POST);
                jsonResponse($response);
                break;
            case 'login':
                validarParametro($_POST);
                $response = $usuarioController->login($_POST);
                jsonResponse($response);
                break;
            case 'verify':
                validarParametro($_POST);
                $response = $usuarioController->verify($_POST);
                jsonResponse($response);
                break;
            case 'logout':
                verificarSesion();
                $response = $usuarioController->logout();
                jsonResponse($response);
                break;
            case 'addRole':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST);
                $response = $rolController->addRole($_POST);
                jsonResponse($response);
                break;
            case 'updateRole':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST['id'], 'string');
                $response = $rolController->updateRole($_POST['id'], $_POST);
                jsonResponse($response);
                break;
            case 'deleteRole':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST['id'], 'string');
                $response = $rolController->deleteRole($_POST['id']);
                jsonResponse($response);
                break;
            case 'addPermission':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST);
                $response = $permisoController->addPermission($_POST);
                jsonResponse($response);
                break;
            case 'updatePermission':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST['id'], 'string');
                $response = $permisoController->updatePermission($_POST['id'], $_POST);
                jsonResponse($response);
                break;
            case 'deletePermission':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_POST['id'], 'string');
                $response = $permisoController->deletePermission($_POST['id']);
                jsonResponse($response);
                break;
            case 'updateProfile':
                verificarSesion();
                validarParametro($_SESSION['user_id'], 'string');
                $response = $usuarioController->updateProfile($_SESSION['user_id'], $_POST);
                jsonResponse($response);
                break;
            default:
                jsonResponse(["message" => "Acción no permitida"], 400);
                break;
        }
        break;

    case 'GET':
        validarParametro($_GET['action'], 'string');
        switch ($_GET['action']) {
            case 'getAllRoles':
                verificarSesion();
                verificarRol('admin');
                $response = $rolController->getAllRoles();
                jsonResponse($response);
                break;
            case 'getRoleById':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_GET['id'], 'string');
                $response = $rolController->getRoleById($_GET['id']);
                jsonResponse($response);
                break;
            case 'getAllPermissions':
                verificarSesion();
                verificarRol('admin');
                $response = $permisoController->getAllPermissions();
                jsonResponse($response);
                break;
            case 'getPermissionById':
                verificarSesion();
                verificarRol('admin');
                validarParametro($_GET['id'], 'string');
                $response = $permisoController->getPermissionById($_GET['id']);
                jsonResponse($response);
                break;
            case 'getProfile':
                verificarSesion();
                validarParametro($_SESSION['user_id'], 'string');
                $response = $usuarioController->getProfile($_SESSION['user_id']);
                jsonResponse($response);
                break;
            default:
                jsonResponse(["message" => "Acción no permitida"], 400);
                break;
        }
        break;
    default:
        jsonResponse(["message" => "Método no permitido"], 405);
        break;
}