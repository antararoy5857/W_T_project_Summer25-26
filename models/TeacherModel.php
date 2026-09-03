<?php

require_once __DIR__ . '/Database.php';

class TeacherModel {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCourses() {
        $result = $this->db->query("SELECT * FROM courses ORDER BY id ASC");
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
        return $courses;
    }

    public function getSubmissions() {
        $result = $this->db->query("SELECT * FROM submissions ORDER BY id ASC");
        $submissions = [];
        while ($row = $result->fetch_assoc()) {
            $submissions[] = [
                'submission_row_id' => $row['id'],
                'id' => $row['student_id'],
                'name' => $row['student_name'],
                'assignment_id' => $row['assignment_id'],
                'assignment_title' => $row['assignment_title'],
                'date' => $row['submission_date'],
                'status' => $row['status'],
                'file' => $row['file'],
                'marks' => $row['marks'],
                'feedback' => $row['feedback'],
                'is_graded' => (bool)$row['is_graded'],
                'resubmit_allowed' => (bool)$row['resubmit_allowed']
            ];
        }
        return $submissions;
    }

    public function publishMarks($marksData, $feedbackData, $resubmitData = []) {
        $submissions = $this->getSubmissions();

        foreach ($submissions as $index => $sub) {
            $rowId = $sub['submission_row_id'];
            $newMarks = isset($marksData[$index]) && $marksData[$index] !== '' ? (float)$marksData[$index] : $sub['marks'];
            $isGraded = isset($marksData[$index]) && $marksData[$index] !== '' ? 1 : ($sub['is_graded'] ? 1 : 0);
            $newFeedback = isset($feedbackData[$index]) ? trim($feedbackData[$index]) : $sub['feedback'];
            $allowResubmit = in_array($sub['id'], $resubmitData) ? 1 : 0;

            $stmt = $this->db->prepare("UPDATE submissions SET marks = ?, feedback = ?, is_graded = ?, resubmit_allowed = ? WHERE id = ?");
            $stmt->bind_param("dsiii", $newMarks, $newFeedback, $isGraded, $allowResubmit, $rowId);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    }
}
