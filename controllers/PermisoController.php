<?php
require_once __DIR__ . '/../models/Permiso.php';
require_once __DIR__ . '/../views/response.php';

class PermisoController {
    private $permisoModel;

    public function __construct() {
        $this->permisoModel = new Permiso();
    }
}