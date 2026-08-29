<?php

class StudentModel {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->initDefaultData();
    }

    private function initDefaultData() {
        if (!isset($_SESSION['assignments'])) {
            $_SESSION['assignments'] = [
                ['id' => 1, 'title' => 'Responsive Web Design', 'course' => 'CSC 3215: Web Technologies', 'course_code' => 'CSC3215', 'deadline' => '2026-08-20', 'marks' => 20, 'status' => 'Pending', 'description' => 'Create a fully responsive layout using flexbox and grid.'],
                ['id' => 2, 'title' => 'PHP Form Validation', 'course' => 'CSC 3215: Web Technologies', 'course_code' => 'CSC3215', 'deadline' => '2026-08-25', 'marks' => 20, 'status' => 'Submitted', 'description' => 'Implement server-side & client-side PHP form validation.'],
                ['id' => 3, 'title' => 'Database Schema Design', 'course' => 'CSC 3105: Database Systems', 'course_code' => 'CSC3105', 'deadline' => '2026-08-28', 'marks' => 15, 'status' => 'Pending', 'description' => 'Design ER diagram and normalized relational tables.']
            ];
        }

        if (!isset($_SESSION['submissions'])) {
            $_SESSION['submissions'] = [
                [
                    'id' => '23-54523-3',
                    'name' => 'Md. Shihab Shikdar',
                    'assignment_id' => 2,
                    'assignment_title' => 'PHP Form Validation',
                    'date' => '2026-08-24',
                    'status' => 'On Time',
                    'file' => 'shihab_php_val.zip',
                    'marks' => 18,
                    'feedback' => 'Good Work',
                    'is_graded' => true,
                    'resubmit_allowed' => false
                ],
                [
                    'id' => '24-56434-1',
                    'name' => 'Md Momen Sha',
                    'assignment_id' => 1,
                    'assignment_title' => 'Responsive Web Design',
                    'date' => '2026-08-22',
                    'status' => 'Late Submission',
                    'file' => 'assignment1_momen.zip',
                    'marks' => null,
                    'feedback' => '',
                    'is_graded' => false,
                    'resubmit_allowed' => false
                ],
                [
                    'id' => '22-48652-3',
                    'name' => 'Antara Roy',
                    'assignment_id' => 1,
                    'assignment_title' => 'Responsive Web Design',
                    'date' => '2026-08-19',
                    'status' => 'On Time',
                    'file' => 'antara_web_task.zip',
                    'marks' => 19,
                    'feedback' => 'Excellent design!',
                    'is_graded' => true,
                    'resubmit_allowed' => false
                ],
                [
                    'id' => '22-99999-3',
                    'name' => 'Tanvir Ahmed',
                    'assignment_id' => 1,
                    'assignment_title' => 'Responsive Web Design',
                    'date' => '—',
                    'status' => 'Pending',
                    'file' => 'Not Submitted',
                    'marks' => null,
                    'feedback' => '',
                    'is_graded' => false,
                    'resubmit_allowed' => false
                ]
            ];
        }
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
        $studentId = $_SESSION['user']['id'] ?? '23-54523-3';
        $total = count($_SESSION['assignments']);
        $submitted = 0;
        $published = 0;

        foreach ($_SESSION['submissions'] as $sub) {
            if ($sub['id'] === $studentId && $sub['file'] !== 'Not Submitted') {
                $submitted++;
                if ($sub['is_graded']) {
                    $published++;
                }
            }
        }

        $pending = max(0, $total - $submitted);

        return [
            'total' => $total,
            'submitted' => $submitted,
            'pending' => $pending,
            'results_published' => $published
        ];
    }

    public function getAssignments() {
        $studentId = $_SESSION['user']['id'] ?? '23-54523-3';
        $assignments = $_SESSION['assignments'];

        // Determine submission status per assignment for current student
        foreach ($assignments as &$asn) {
            $asn['status'] = 'Pending';
            foreach ($_SESSION['submissions'] as $sub) {
                if ($sub['id'] === $studentId && strcasecmp($sub['assignment_title'], $asn['title']) === 0 && $sub['file'] !== 'Not Submitted') {
                    $asn['status'] = 'Submitted';
                    break;
                }
            }
        }
        return $assignments;
    }

    public function getResults() {
        $studentId = $_SESSION['user']['id'] ?? '23-54523-3';
        $results = [];

        foreach ($_SESSION['submissions'] as $sub) {
            if ($sub['id'] === $studentId && $sub['is_graded']) {
                $totalMarks = 20;
                foreach ($_SESSION['assignments'] as $asn) {
                    if (strcasecmp($asn['title'], $sub['assignment_title']) === 0) {
                        $totalMarks = $asn['marks'];
                        break;
                    }
                }
                $results[] = [
                    'title' => $sub['assignment_title'],
                    'course' => 'Web Technology',
                    'obtained' => $sub['marks'],
                    'total' => $totalMarks,
                    'feedback' => !empty($sub['feedback']) ? $sub['feedback'] : 'Graded',
                    'status' => 'Published'
                ];
            }
        }
        return $results;
    }

    public function submitAssignment($assignmentTitle, $fileName, $comment = '') {
        $studentUser = $_SESSION['user'] ?? null;
        $studentId = $studentUser['id'] ?? '23-54523-3';
        $studentName = $studentUser['name'] ?? 'Md. Shihab Shikdar';

        $found = false;
        foreach ($_SESSION['submissions'] as &$sub) {
            if ($sub['id'] === $studentId && strcasecmp($sub['assignment_title'], $assignmentTitle) === 0) {
                $sub['file'] = $fileName;
                $sub['date'] = date('Y-m-d');
                $sub['status'] = 'On Time';
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['submissions'][] = [
                'id' => $studentId,
                'name' => $studentName,
                'assignment_id' => 1,
                'assignment_title' => $assignmentTitle,
                'date' => date('Y-m-d'),
                'status' => 'On Time',
                'file' => $fileName,
                'marks' => null,
                'feedback' => $comment,
                'is_graded' => false,
                'resubmit_allowed' => false
            ];
        }

        return true;
    }
}
