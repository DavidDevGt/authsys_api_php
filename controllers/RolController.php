<?php
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../views/response.php';

class RolController {
    private $rolModel;

    public function __construct() {
        $this->rolModel = new Rol();
    }

    public function getAllRoles() {
        // Devolver todos los roles
        return $this->rolModel->getAllRoles();
    }

    public function getRoleByName($name) {
        // Devolver el rol con el nombre
        return $this->rolModel->getRoleByName($name);
    }

    public function addRole($data) {
        // Agregar un rol
        return $this->rolModel->addRole($data['name'], $data['description']);
    }

    public function updateRole($id, $data) {
        // Actualizar un rol
        return $this->rolModel->updateRole($id, $data['name'], $data['description']);
    }

    public function deleteRole($roleId) {
        // Eliminar un rol
        return $this->rolModel->deleteRole($roleId);
    }

    public function assignPermission($roleId, $permissionId) {
        // Asignar un permiso a un rol
        return $this->rolModel->assignPermission($roleId, $permissionId);
    }

    public function revokePermission($roleId, $permissionId) {
        // Revocar un permiso de un rol
        return $this->rolModel->revokePermission($roleId, $permissionId);
    }
}