<?php

class AssignmentModel {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['assignments'])) {
            $_SESSION['assignments'] = [
                ['id' => 1, 'title' => 'Assignment 1 - HTML & CSS', 'course' => 'CSC 3215: Web Technologies', 'course_code' => 'CSC3215', 'deadline' => '2026-08-20', 'marks' => 20, 'status' => 'Pending', 'description' => 'Build a responsive layout using modern HTML & CSS.'],
                ['id' => 2, 'title' => 'Assignment 2 - PHP Basics', 'course' => 'CSC 3215: Web Technologies', 'course_code' => 'CSC3215', 'deadline' => '2026-08-25', 'marks' => 20, 'status' => 'Pending', 'description' => 'Implement server-side form handling and validation in PHP.']
            ];
        }
    }

    public function createAssignment($data) {
        $title = trim($data['title'] ?? 'New Assignment');
        $courseCode = trim($data['course'] ?? 'CSC3215');
        $dueDate = trim($data['dueDate'] ?? date('Y-m-d'));
        $totalMarks = (int)($data['totalMarks'] ?? 20);
        $description = trim($data['description'] ?? '');

        $courseName = $courseCode;
        if (isset($_SESSION['courses'])) {
            foreach ($_SESSION['courses'] as $c) {
                if ($c['code'] === $courseCode) {
                    $courseName = $c['name'];
                    break;
                }
            }
        }

        $newAssignment = [
            'id' => count($_SESSION['assignments']) + 1,
            'title' => $title,
            'course' => $courseName,
            'course_code' => $courseCode,
            'deadline' => $dueDate,
            'marks' => $totalMarks,
            'status' => 'Pending',
            'description' => $description
        ];

        $_SESSION['assignments'][] = $newAssignment;
        return true;
    }

    public function getAllAssignments() {
        return $_SESSION['assignments'];
    }

    public function publishMarks($assignmentId, $postData) {
        if (!isset($_SESSION['submissions'])) return false;

        $marks = $postData['marks'] ?? [];
        $feedback = $postData['feedback'] ?? [];
        $resubmit = $postData['resubmit'] ?? [];

        foreach ($_SESSION['submissions'] as $index => &$sub) {
            if (isset($marks[$index]) && $marks[$index] !== '') {
                $sub['marks'] = (float)$marks[$index];
                $sub['is_graded'] = true;
            }
            if (isset($feedback[$index])) {
                $sub['feedback'] = trim($feedback[$index]);
            }
            if (in_array($sub['id'], $resubmit)) {
                $sub['resubmit_allowed'] = true;
            }
        }
        return true;
    }
}
