<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $model;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->model = new User($db);
    }

    public function showLogin()
    {
        require __DIR__ . '/../views/login.php';
    }

    public function showRegistro()
    {
        require __DIR__ . '/../views/register.php';
    }

    public function login()
    {
        header('Content-Type: application/json');

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            echo json_encode(['response' => "01", 'message' => "Debe completar todos los campos"]);
            return;
        }

        $user = $this->model->login($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['user'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];

            echo json_encode([
                'response' => "00",
                'rol' => $user['rol'],
                'message' => "Login exitoso"
            ]);
        } else {
            echo json_encode(['response' => "01", 'message' => "Error de autentificación"]);
        }
    }

    public function registro()
    {
        header('Content-Type: application/json');

        $username = trim($_POST['username'] ?? '');
        $passwordTexto = trim($_POST['password'] ?? '');

        if ($username === '' || $passwordTexto === '') {
            echo json_encode(['response' => "01", 'message' => "Debe completar todos los campos"]);
            return;
        }

        $password = password_hash($passwordTexto, PASSWORD_DEFAULT);

        $result = $this->model->create($username, $password);

        if ($result) {
            echo json_encode(['response' => "00", 'message' => "Registro exitoso"]);
        } else {
            echo json_encode(['response' => "01", 'message' => "Error al registrar o usuario repetido"]);
        }
    }

    public function logout()
    {
        header('Content-Type: application/json');
        session_destroy();
        echo json_encode(['response' => "00", 'message' => "Sesión cerrada"]);
    }
}
