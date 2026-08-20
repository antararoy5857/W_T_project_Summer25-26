<?php

require_once 'models/TeacherModel.php';

class TeacherController {
    private $teacherModel;

    public function __construct() {
        $this->teacherModel = new TeacherModel();
    }

    public function index() {
        $courses = $this->teacherModel->getCourses();
        $submissions = $this->teacherModel->getSubmissions();

        require_once 'views/teacher_view.php';
    }

    public function modification() {
        $courses = $this->teacherModel->getCourses();
        $submissions = $this->teacherModel->getSubmissions();

        require_once 'views/teacher_mod_view.php';
    }
}
