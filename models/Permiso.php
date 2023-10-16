<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../views/response.php';

class Permiso {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPermissions() {
        // Devolver todos los permisos
    }

    public function getPermissionByName($name) {
        // Devolver el permiso con el nombre indicado
    }

    public function addPermission($name) {
        // Agregar un permiso
    }

    public function updatePermission($id, $name) {
        // Actualizar un permiso
    }

    public function deletePermission($id) {
        // Eliminar un permiso
    }
}