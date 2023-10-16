<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../views/response.php';

class Rol {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllRoles() {
        // Devolver todos los roles
    }

    public function getRoleByName($name) {
        // Devolver el rol con el nombre indicado
    }

    public function addRole($name, $description) {
        // Agregar un rol
    }

    public function updateRole($id, $name, $description) {
        // Actualizar un rol
    }

    public function deleteRole($id) {
        // Eliminar un rol
    }

    public function listPermission($roleId, $permissionId) {
        // Asignar un permiso a un rol
    }

    public function revokePermission($roleId, $permissionId) {
        // Revocar un permiso de un rol
    }
}