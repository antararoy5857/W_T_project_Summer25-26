<?php

require_once __DIR__ . '/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate($usernameOrEmail, $password, $role = null) {
        $usernameOrEmail = trim($usernameOrEmail);

        if (!empty($role)) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE (LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)) AND password = ? AND role = ?");
            $stmt->bind_param("ssss", $usernameOrEmail, $usernameOrEmail, $password, $role);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE (LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)) AND password = ?");
            $stmt->bind_param("sss", $usernameOrEmail, $usernameOrEmail, $password);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user ?: false;
    }

    public function getUserByUsernameOrEmail($identifier) {
        $identifier = trim($identifier);
        $stmt = $this->db->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user ?: null;
    }

    public function registerUser($data) {
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $name = trim($data['name'] ?? '');
        $role = trim($data['role'] ?? 'student');
        $department = trim($data['department'] ?? 'Computer Science');
        $password = $data['password'] ?? '';
        $phone = trim($data['phone'] ?? '+880-1700000000');

        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All required fields must be filled.'];
        }

        if ($this->getUserByUsernameOrEmail($username) || $this->getUserByUsernameOrEmail($email)) {
            return ['success' => false, 'message' => 'User ID or Email already registered.'];
        }

        $formattedName = !empty($name) ? $name : $username;
        $userRole = strtolower($role) === 'teacher' ? 'teacher' : 'student';

        $stmt = $this->db->prepare("INSERT INTO users (username, email, name, id_code, department, role, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $email, $formattedName, $username, $department, $userRole, $phone, $password);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            return ['success' => true, 'message' => 'Registration successful! Please log in.'];
        }
        return ['success' => false, 'message' => 'Failed to register user.'];
    }

    public function resetPassword($identifier, $newPassword) {
        $identifier = trim($identifier);
        $user = $this->getUserByUsernameOrEmail($identifier);
        if (!$user) {
            return ['success' => false, 'message' => 'User ID or Email not found.'];
        }

        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)");
        $stmt->bind_param("sss", $newPassword, $identifier, $identifier);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            return ['success' => true, 'message' => 'Password reset successfully! Log in with your new password.'];
        }
        return ['success' => false, 'message' => 'Failed to reset password.'];
    }

    // --- Admin Helpers ---
    public function getTeachers() {
        $result = $this->db->query("SELECT * FROM users WHERE role = 'teacher'");
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
        return $teachers;
    }

    public function addTeacher($name, $email, $username) {
        if (!empty($name) && !empty($username)) {
            $dept = 'CSE';
            $role = 'teacher';
            $pass = '123';
            $phone = '+880-1800000000';
            $stmt = $this->db->prepare("INSERT INTO users (username, email, name, id_code, department, role, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $username, $email, $name, $username, $dept, $role, $phone, $pass);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function addCourse($code, $name) {
        if (!empty($code) && !empty($name)) {
            $codeUpper = strtoupper($code);
            $credit = 3;
            $semester = 'Summer 2025-2026';
            $stmt = $this->db->prepare("INSERT INTO courses (code, name, credit, semester) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name)");
            $stmt->bind_param("ssis", $codeUpper, $name, $credit, $semester);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function getSystemReport() {
        $studentCnt = $this->db->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'student'")->fetch_assoc()['cnt'] ?? 0;
        $teacherCnt = $this->db->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'teacher'")->fetch_assoc()['cnt'] ?? 0;
        $courseCnt = $this->db->query("SELECT COUNT(*) as cnt FROM courses")->fetch_assoc()['cnt'] ?? 0;
        $assignmentCnt = $this->db->query("SELECT COUNT(*) as cnt FROM assignments")->fetch_assoc()['cnt'] ?? 0;
        $submissionCnt = $this->db->query("SELECT COUNT(*) as cnt FROM submissions")->fetch_assoc()['cnt'] ?? 0;

        return [
            'students' => (int)$studentCnt,
            'teachers' => (int)$teacherCnt,
            'courses' => (int)$courseCnt,
            'assignments' => (int)$assignmentCnt,
            'submissions' => (int)$submissionCnt
        ];
    }
}
