<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/TeacherModel.php';

class AssignmentModel {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function createAssignment($data) {
        $title = trim($data['title'] ?? 'New Assignment');
        $courseCode = trim($data['course'] ?? 'CSC3215');
        $dueDate = trim($data['dueDate'] ?? date('Y-m-d'));
        $totalMarks = (int)($data['totalMarks'] ?? 20);
        $description = trim($data['description'] ?? '');

        $courseName = $courseCode;
        $stmtC = $this->db->prepare("SELECT name FROM courses WHERE code = ? LIMIT 1");
        $stmtC->bind_param("s", $courseCode);
        $stmtC->execute();
        $resC = $stmtC->get_result()->fetch_assoc();
        if ($resC) {
            $courseName = $resC['name'];
        }
        $stmtC->close();

        $status = 'Pending';
        $stmt = $this->db->prepare("INSERT INTO assignments (title, course, course_code, deadline, marks, status, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $title, $courseName, $courseCode, $dueDate, $totalMarks, $status, $description);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllAssignments() {
        $result = $this->db->query("SELECT * FROM assignments ORDER BY id ASC");
        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }
        return $assignments;
    }

    public function publishMarks($assignmentId, $postData) {
        $teacherModel = new TeacherModel();
        return $teacherModel->publishMarks($postData['marks'] ?? [], $postData['feedback'] ?? [], $postData['resubmit'] ?? []);
    }
}
