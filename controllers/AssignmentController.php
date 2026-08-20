<?php

require_once 'models/AssignmentModel.php';

class AssignmentController {
    private $assignmentModel;

    public function __construct() {
        $this->assignmentModel = new AssignmentModel();
    }

    public function createAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignmentModel->createAssignment($_POST);
            echo "<script>alert('Assignment created successfully!'); window.location.href='index.php?page=teacher';</script>";
        }
    }

    public function publishMarks() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignmentModel->publishMarks($_POST['filterAssignment'] ?? null, $_POST);
            echo "<script>alert('Marks published successfully!'); window.location.href='index.php?page=teacher';</script>";
        }
    }

    public function submitAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<script>alert('Assignment submitted successfully!'); window.location.href='index.php?page=student';</script>";
        }
    }
}
