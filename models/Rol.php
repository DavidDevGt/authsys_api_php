<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../views/response.php';

class Rol
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllRoles()
    {
        // Devolver todos los roles
        $query = "SELECT * FROM user_roles";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRoleById($id)
    {
        // Devolver el rol con el ID indicado
        $stmt = $this->conn->prepare("SELECT * FROM user_roles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function addRole($name, $description)
    {
        // Agregar un rol
        $stmt = $this->conn->prepare("INSERT INTO user_roles (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            return ["message" => "Rol agregado exitosamente"];
        } else {
            return ["message" => "Error al agregar el rol"];
        }
    }

    public function updateRole($id, $name, $description)
    {
        // Actualizar un rol
        $stmt = $this->conn->prepare("UPDATE user_roles SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        if ($stmt->execute()) {
            return ["message" => "Rol actualizado exitosamente"];
        } else {
            return ["message" => "Error al actualizar el rol"];
        }
    }

    public function deleteRole($id)
    {
        // Eliminar un rol
        $stmt = $this->conn->prepare("DELETE FROM user_roles WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return ["message" => "Rol eliminado exitosamente"];
        } else {
            return ["message" => "Error al eliminar el rol"];
        }
    }

    public function assignPermission($roleId, $permissionId)
    {
        // Asignar un permiso a un rol
        $stmt = $this->conn->prepare("INSERT INTO user_role_assignments (user_auth_id, role_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $roleId, $permissionId);
        if ($stmt->execute()) {
            return ["message" => "Permiso asignado al rol exitosamente"];
        } else {
            return ["message" => "Error al asignar el permiso al rol"];
        }
    }
    public function revokePermission($roleId, $permissionId)
    {
        // Revocar un permiso de un rol
        $stmt = $this->conn->prepare("DELETE FROM user_role_assignments WHERE user_auth_id = ? AND role_id = ?");
        $stmt->bind_param("ii", $roleId, $permissionId);
        if ($stmt->execute()) {
            return ["message" => "Permiso revocado al rol exitosamente"];
        } else {
            return ["message" => "Error al revocar el permiso al rol"];
        }
    }
}
