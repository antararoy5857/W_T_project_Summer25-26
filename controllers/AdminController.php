<?php

require_once 'models/UserModel.php';
require_once 'models/TeacherModel.php';

class AdminController {
    private $userModel;
    private $teacherModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->userModel = new UserModel();
        $this->teacherModel = new TeacherModel();
    }

    public function index() {
        $teachers = $this->userModel->getTeachers();
        $report = $this->userModel->getSystemReport();
        $courses = $this->teacherModel->getCourses();
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
