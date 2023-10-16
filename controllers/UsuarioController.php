<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/AuthController.php';


class UsuarioController
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function register($data)
    {
        return $this->model->register($data);
    }

    public function login($data)
    {
        return $this->model->login($data);
    }

    public function verify($data)
    {
        return $this->model->verify($data);
    }
}