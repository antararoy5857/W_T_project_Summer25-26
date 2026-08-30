<?php

require_once 'models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function showLogin($error = null, $success = null) {
        require_once 'views/login_view.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? null;

            if (empty($username) || empty($password)) {
                $this->showLogin('Please enter both User ID/Email and Password.');
                return;
            }

            $user = $this->userModel->authenticate($username, $password, $role);

            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['flash_success'] = 'Welcome back, ' . htmlspecialchars($user['name']) . '!';

                if ($user['role'] === 'admin') {
                    header('Location: index.php?page=admin');
                } else if ($user['role'] === 'teacher') {
                    header('Location: index.php?page=teacher');
                } else {
                    header('Location: index.php?page=student');
                }
                exit;
            } else {
                $this->showLogin('Invalid credentials or selected role!');
            }
        } else {
            $this->showLogin();
        }
    }

    public function showRegister($error = null, $success = null) {
        require_once 'views/register_view.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userModel->registerUser($_POST);
            if ($result['success']) {
                $this->showLogin(null, $result['message']);
            } else {
                $this->showRegister($result['message']);
            }
        } else {
            $this->showRegister();
        }
    }

    public function showForgotPassword($error = null, $success = null) {
        require_once 'views/forgot_password_view.php';
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($newPassword)) {
                $this->showForgotPassword('User ID/Email and New Password are required.');
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $this->showForgotPassword('New Passwords do not match.');
                return;
            }

            $result = $this->userModel->resetPassword($username, $newPassword);
            if ($result['success']) {
                $this->showLogin(null, $result['message']);
            } else {
                $this->showForgotPassword($result['message']);
            }
        } else {
            $this->showForgotPassword();
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        $_SESSION['flash_success'] = 'You have been successfully logged out.';
        header('Location: index.php?page=login');
        exit;
    }
}
