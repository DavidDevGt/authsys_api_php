<?php
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../views/response.php';

class RolController {
    private $rolModel;

    public function __construct() {
        $this->rolModel = new Rol();
    }
}