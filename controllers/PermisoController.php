<?php
require_once __DIR__ . '/../models/Permiso.php';
require_once __DIR__ . '/../views/response.php';

class PermisoController {
    private $permisoModel;

    public function __construct() {
        $this->permisoModel = new Permiso();
    }

    public function getAllPermissions() {
        // Devolver todos los permisos
        return $this->permisoModel->getAllPermissions();
    }

    public function getPermissionByName($name) {
        // Devolver el permiso con el nombre indicado
        return $this->permisoModel->getPermissionByName($name);
    }

    public function addPermission($data) {
        // Agregar un permiso
        return $this->permisoModel->addPermission($data);
    }

    public function updatePermission($id, $data) {
        // Actualizar un permiso
        return $this->permisoModel->updatePermission($id, $data);
    }

    public function deletePermission($id) {
        // Eliminar un permiso
        return $this->permisoModel->deletePermission($id);
    }
}