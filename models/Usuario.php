<?php
require_once '../config/db.php';

class Usuario {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($data) {
        // Implementar la función register
    }

    public function login($data) {
        // Implementar la función login
    }

    public function verify($data) {
        // Implementar la función verify
    }
}
