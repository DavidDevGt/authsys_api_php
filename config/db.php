<?php

class Database
{
    private $host = "localhost";
    private $db_name = "auth_template";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            // Establecer el conjunto de caracteres.
            $this->conn->set_charset("utf8mb4");

        } catch (Exception $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
