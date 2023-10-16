<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../views/response.php';

class Permiso
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPermissions()
    {
        // Devolver todos los permisos
        $query = "SELECT * FROM permissions";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPermissionById($id)
    {
        // Devolver el permiso con el ID indicado
        $stmt = $this->conn->prepare("SELECT * FROM permissions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addPermission($name)
    {
        // Agregar un permiso
        $stmt = $this->conn->prepare("INSERT INTO permissions (permission) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            return ["message" => "Permiso agregado correctamente"];
        } else {
            return ["message" => "Error al agregar el permiso"];
        }
    }

    public function updatePermission($id, $name)
    {
        // Actualizar un permiso
        $stmt = $this->conn->prepare("UPDATE permissions SET permission = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            return ["message" => "Permiso actualizado correctamente"];
        } else {
            return ["message" => "Error al actualizar el permiso"];
        }
    }

    public function deletePermission($id)
    {
        // Eliminar un permiso
        $stmt = $this->conn->prepare("DELETE FROM permissions WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return ["message" => "Permiso eliminado correctamente"];
        } else {
            return ["message" => "Error al eliminar el permiso"];
        }
    }
}
