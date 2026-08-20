<?php

require_once 'models/StudentModel.php';

class StudentController {
    private $studentModel;

    public function __construct() {
        $this->studentModel = new StudentModel();
    }

    public function index() {
        $info = $this->studentModel->getStudentInfo();
        $stats = $this->studentModel->getDashboardStats();
        $assignments = $this->studentModel->getAssignments();
        $results = $this->studentModel->getResults();

        require_once 'views/student_view.php';
    }
}
