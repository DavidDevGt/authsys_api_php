<?php
require_once __DIR__ . '/../models/Permiso.php';
require_once __DIR__ . '/../views/response.php';

class PermisoController
{
    private $permisoModel;

    public function __construct()
    {
        $this->permisoModel = new Permiso();
    }

    public function getAllPermissions()
    {
        // Devolver todos los permisos
        return $this->permisoModel->getAllPermissions();
    }

    public function getPermissionByName($name)
    {
        // Devolver el permiso con el nombre indicado
        return $this->permisoModel->getPermissionByName($name);
    }

    public function addPermission($data)
    {
        // Agregar un permiso
        if (!isset($data['name'])) {
            return ["message" => "El nombre del permiso es requerido"];
        }

        $permissionName = $data['name'];
        return $this->permisoModel->addPermission($permissionName);
    }

    public function updatePermission($id, $data)
    {
        // Asegurarte de que el nombre del permiso esté establecido
        if (!isset($data['name'])) {
            return ["message" => "El nombre del permiso es requerido"];
        }

        $permissionName = $data['name'];
        return $this->permisoModel->updatePermission($id, $permissionName);
    }

    public function deletePermission($id)
    {
        // Eliminar un permiso
        return $this->permisoModel->deletePermission($id);
    }
}
