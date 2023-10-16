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
    }

    public function getPermissionByName($name) {
        // Devolver el permiso con el nombre indicado
    }

    public function addPermission($data) {
        // Agregar un permiso
    }

    public function updatePermission($id, $data) {
        // Actualizar un permiso
    }

    public function deletePermission($id) {
        // Eliminar un permiso
    }
}