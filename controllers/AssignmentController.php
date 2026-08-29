<?php

require_once 'models/AssignmentModel.php';
require_once 'models/StudentModel.php';

class AssignmentController {
    private $assignmentModel;
    private $studentModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->assignmentModel = new AssignmentModel();
        $this->studentModel = new StudentModel();
    }

    public function createAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignmentModel->createAssignment($_POST);
            $_SESSION['flash_success'] = 'Assignment created and published successfully!';
            header('Location: index.php?page=teacher');
            exit;
        }
    }

    public function publishMarks() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignmentModel->publishMarks($_POST['filterAssignment'] ?? null, $_POST);
            $_SESSION['flash_success'] = 'Marks and feedback published successfully!';
            header('Location: index.php?page=teacher_mod');
            exit;
        }
    }

    public function submitAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignmentTitle = $_POST['assignment'] ?? 'Assignment';
            $comment = $_POST['comment'] ?? '';
            $fileName = 'submission_' . time() . '.pdf';

            if (isset($_FILES['assignmentFile']) && $_FILES['assignmentFile']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['assignmentFile']['name']);
            }

            $this->studentModel->submitAssignment($assignmentTitle, $fileName, $comment);
            $_SESSION['flash_success'] = 'Assignment submitted successfully!';
            header('Location: index.php?page=student');
            exit;
        }
    }
}
