<?php

require_once 'controllers/StudentController.php';
require_once 'controllers/TeacherController.php';
require_once 'controllers/AssignmentController.php';

$page = $_GET['page'] ?? 'student';
$action = $_GET['action'] ?? null;

if ($action === 'create_assignment') {
    $controller = new AssignmentController();
    $controller->createAssignment();
} else if ($action === 'publish_marks') {
    $controller = new AssignmentController();
    $controller->publishMarks();
} else if ($action === 'submit_assignment') {
    $controller = new AssignmentController();
    $controller->submitAssignment();
} else {
    switch ($page) {
        case 'teacher':
            $controller = new TeacherController();
            $controller->index();
            break;
        case 'teacher_mod':
            $controller = new TeacherController();
            $controller->modification();
            break;
        case 'student':
        default:
            $controller = new StudentController();
            $controller->index();
            break;
    }
}
