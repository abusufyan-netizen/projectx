<?php

namespace Hub\Controllers;

use Hub\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showWelcome() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/dashboard');
            exit;
        }
        $this->render('welcome', ['title' => 'Welcome']);
    }

    public function showLogin() {
        $this->render('auth/login');
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_dept'] = $user['department'];
            $_SESSION['user_sem'] = $user['semester'];
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/dashboard');
            exit;
        }

        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/login');
        exit;
    }

    public function showRegister() {
        $this->render('auth/register');
    }

    public function register() {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'Student',
            'department' => $_POST['department'] ?? 'Computer Science',
            'semester' => $_POST['semester'] ?? 1
        ];

        if ($this->userModel->create($data)) {
            $_SESSION['success'] = 'Registration successful. Please login.';
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/login');
            exit;
        }

        $_SESSION['error'] = 'Registration failed.';
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/register');
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
        exit;
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../../views/layouts/main.php";
    }
}
