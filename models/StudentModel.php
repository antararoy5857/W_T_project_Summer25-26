<?php

require_once __DIR__ . '/Database.php';

class StudentModel {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function getStudentInfo() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'student') {
            return $_SESSION['user'];
        }
        return [
            'name' => 'Md. Shihab Shikdar',
            'id' => '23-54523-3',
            'department' => 'Computer Science and Engineering',
            'role' => 'Student',
            'email' => 'shihab@gmail.com',
            'phone' => '+880-171717155311'
        ];
    }

    public function getDashboardStats() {
        $studentUser = $this->getStudentInfo();
        $studentId = $studentUser['id_code'] ?? ($studentUser['id'] ?? '23-54523-3');

        $totalRes = $this->db->query("SELECT COUNT(*) as cnt FROM assignments");
        $total = (int)($totalRes->fetch_assoc()['cnt'] ?? 0);

        $stmtSub = $this->db->prepare("SELECT COUNT(*) as cnt FROM submissions WHERE student_id = ? AND file != 'Not Submitted'");
        $stmtSub->bind_param("s", $studentId);
        $stmtSub->execute();
        $submitted = (int)($stmtSub->get_result()->fetch_assoc()['cnt'] ?? 0);
        $stmtSub->close();

        $stmtPub = $this->db->prepare("SELECT COUNT(*) as cnt FROM submissions WHERE student_id = ? AND is_graded = 1");
        $stmtPub->bind_param("s", $studentId);
        $stmtPub->execute();
        $published = (int)($stmtPub->get_result()->fetch_assoc()['cnt'] ?? 0);
        $stmtPub->close();

        $pending = max(0, $total - $submitted);

        return [
            'total' => $total,
            'submitted' => $submitted,
            'pending' => $pending,
            'results_published' => $published
        ];
    }

    public function getAssignments() {
        $studentUser = $this->getStudentInfo();
        $studentId = $studentUser['id_code'] ?? ($studentUser['id'] ?? '23-54523-3');

        $res = $this->db->query("SELECT * FROM assignments ORDER BY id ASC");
        $assignments = [];

        while ($row = $res->fetch_assoc()) {
            $row['status'] = 'Pending';
            $stmt = $this->db->prepare("SELECT * FROM submissions WHERE student_id = ? AND LOWER(assignment_title) = LOWER(?) AND file != 'Not Submitted'");
            $stmt->bind_param("ss", $studentId, $row['title']);
            $stmt->execute();
            $subRes = $stmt->get_result();
            if ($subRes->num_rows > 0) {
                $row['status'] = 'Submitted';
            }
            $stmt->close();

            $assignments[] = $row;
        }

        return $assignments;
    }

    public function getResults() {
        $studentUser = $this->getStudentInfo();
        $studentId = $studentUser['id_code'] ?? ($studentUser['id'] ?? '23-54523-3');

        $stmt = $this->db->prepare("SELECT s.*, a.course, a.marks as total_marks FROM submissions s LEFT JOIN assignments a ON LOWER(s.assignment_title) = LOWER(a.title) WHERE s.student_id = ? AND s.is_graded = 1");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $res = $stmt->get_result();

        $results = [];
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'title' => $row['assignment_title'],
                'course' => $row['course'] ?? 'Web Technology',
                'obtained' => $row['marks'],
                'total' => $row['total_marks'] ?? 20,
                'feedback' => !empty($row['feedback']) ? $row['feedback'] : 'Graded',
                'status' => 'Published'
            ];
        }
        $stmt->close();
        return $results;
    }

    public function submitAssignment($assignmentTitle, $fileName, $comment = '') {
        $studentUser = $this->getStudentInfo();
        $studentId = $studentUser['id_code'] ?? ($studentUser['id'] ?? '23-54523-3');
        $studentName = $studentUser['name'] ?? 'Md. Shihab Shikdar';
        $today = date('Y-m-d');

        // Find assignment ID if available
        $assignmentId = 1;
        $stmtAssn = $this->db->prepare("SELECT id FROM assignments WHERE LOWER(title) = LOWER(?) LIMIT 1");
        $stmtAssn->bind_param("s", $assignmentTitle);
        $stmtAssn->execute();
        $assnRes = $stmtAssn->get_result()->fetch_assoc();
        if ($assnRes) {
            $assignmentId = (int)$assnRes['id'];
        }
        $stmtAssn->close();

        // Check if submission exists
        $stmtCheck = $this->db->prepare("SELECT id FROM submissions WHERE student_id = ? AND LOWER(assignment_title) = LOWER(?)");
        $stmtCheck->bind_param("ss", $studentId, $assignmentTitle);
        $stmtCheck->execute();
        $existing = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        if ($existing) {
            $stmtUp = $this->db->prepare("UPDATE submissions SET file = ?, submission_date = ?, status = 'On Time', feedback = ? WHERE id = ?");
            $stmtUp->bind_param("sssi", $fileName, $today, $comment, $existing['id']);
            $stmtUp->execute();
            $stmtUp->close();
        } else {
            $status = 'On Time';
            $isGraded = 0;
            $resubmitAllowed = 0;
            $stmtIns = $this->db->prepare("INSERT INTO submissions (student_id, student_name, assignment_id, assignment_title, submission_date, status, file, feedback, is_graded, resubmit_allowed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtIns->bind_param("ssisssssii", $studentId, $studentName, $assignmentId, $assignmentTitle, $today, $status, $fileName, $comment, $isGraded, $resubmitAllowed);
            $stmtIns->execute();
            $stmtIns->close();
        }

        return true;
    }
}
