<?php

class UserModel {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->initUsers();
    }

    private function initUsers() {
        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [
                [
                    'username' => '23-54523-3',
                    'email' => 'shihab@gmail.com',
                    'name' => 'Md. Shihab Shikdar',
                    'id' => '23-54523-3',
                    'department' => 'Computer Science and Engineering',
                    'role' => 'student',
                    'phone' => '+880-171717155311',
                    'password' => '123'
                ],
                [
                    'username' => 'teacher',
                    'email' => 'teacher@aiub.edu',
                    'name' => 'Dr. Mahfuzur Rahman',
                    'id' => 'T-101',
                    'department' => 'Computer Science and Engineering',
                    'role' => 'teacher',
                    'phone' => '+880-1818181818',
                    'password' => '123'
                ]
            ];
        }
    }

    public function authenticate($usernameOrEmail, $password, $role = null) {
        $usernameOrEmail = trim($usernameOrEmail);
        foreach ($_SESSION['users'] as $user) {
            $matchUser = (strcasecmp($user['username'], $usernameOrEmail) === 0 || strcasecmp($user['email'], $usernameOrEmail) === 0);
            $matchPass = ($user['password'] === $password);
            $matchRole = ($role === null || strcasecmp($user['role'], $role) === 0);

            if ($matchUser && $matchPass && $matchRole) {
                return $user;
            }
        }
        return false;
    }

    public function getUserByUsernameOrEmail($identifier) {
        $identifier = trim($identifier);
        foreach ($_SESSION['users'] as $user) {
            if (strcasecmp($user['username'], $identifier) === 0 || strcasecmp($user['email'], $identifier) === 0) {
                return $user;
            }
        }
        return null;
    }

    public function registerUser($data) {
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $name = trim($data['name'] ?? '');
        $role = trim($data['role'] ?? 'student');
        $department = trim($data['department'] ?? 'Computer Science');
        $password = $data['password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All required fields must be filled.'];
        }

        if ($this->getUserByUsernameOrEmail($username) || $this->getUserByUsernameOrEmail($email)) {
            return ['success' => false, 'message' => 'User ID or Email already registered.'];
        }

        $newUser = [
            'username' => $username,
            'email' => $email,
            'name' => !empty($name) ? $name : $username,
            'id' => $username,
            'department' => $department,
            'role' => strtolower($role) === 'teacher' ? 'teacher' : 'student',
            'phone' => trim($data['phone'] ?? '+880-1700000000'),
            'password' => $password
        ];

        $_SESSION['users'][] = $newUser;
        return ['success' => true, 'message' => 'Registration successful! Please log in.'];
    }

    public function resetPassword($identifier, $newPassword) {
        $identifier = trim($identifier);
        foreach ($_SESSION['users'] as $key => $user) {
            if (strcasecmp($user['username'], $identifier) === 0 || strcasecmp($user['email'], $identifier) === 0) {
                $_SESSION['users'][$key]['password'] = $newPassword;
                return ['success' => true, 'message' => 'Password reset successfully! Log in with your new password.'];
            }
        }
        return ['success' => false, 'message' => 'User ID or Email not found.'];
    }
}
