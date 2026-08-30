<?php

require_once 'models/UserModel.php';

class AdminController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->userModel = new UserModel();
    }

    public function index() {
        $teachers = $this->userModel->getTeachers();
        $report = $this->userModel->getSystemReport();
        $courses = $_SESSION['courses'] ?? [];
        require_once 'views/admin_view.php';
    }

    public function addTeacher() {
        $this->userModel->addTeacher($_POST['name'] ?? '', $_POST['email'] ?? '', $_POST['username'] ?? '');
        header('Location: index.php?page=admin');
        exit;
    }

    public function setupCourse() {
        $this->userModel->addCourse($_POST['code'] ?? '', $_POST['name'] ?? '');
        header('Location: index.php?page=admin');
        exit;
    }
}
