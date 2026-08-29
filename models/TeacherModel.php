<?php

class TeacherModel {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->initDefaultData();
    }

    private function initDefaultData() {
        if (!isset($_SESSION['courses'])) {
            $_SESSION['courses'] = [
                ['code' => 'CSC3215', 'name' => 'CSC 3215: Web Technologies'],
                ['code' => 'CSC2210', 'name' => 'CSC 2210: Object Oriented Programming'],
                ['code' => 'CSC3105', 'name' => 'CSC 3105: Database Systems']
            ];
        }

        if (!isset($_SESSION['submissions'])) {
            $_SESSION['submissions'] = [
                [
                    'id' => '23-54523-3',
                    'name' => 'Md. Shihab Shikdar',
                    'assignment_id' => 1,
                    'assignment_title' => 'Responsive Web Design',
                    'date' => '2026-05-10',
                    'status' => 'On Time',
                    'file' => 'view_submission.pdf',
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
                    'date' => '2026-05-12',
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
                    'date' => '2026-05-11',
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

    public function getCourses() {
        return $_SESSION['courses'];
    }

    public function getSubmissions() {
        return $_SESSION['submissions'];
    }

    public function publishMarks($marksData, $feedbackData, $resubmitData = []) {
        if (!isset($_SESSION['submissions'])) return false;

        foreach ($_SESSION['submissions'] as $index => &$sub) {
            if (isset($marksData[$index]) && $marksData[$index] !== '') {
                $sub['marks'] = (float)$marksData[$index];
                $sub['is_graded'] = true;
            }
            if (isset($feedbackData[$index])) {
                $sub['feedback'] = trim($feedbackData[$index]);
            }
            if (in_array($sub['id'], $resubmitData)) {
                $sub['resubmit_allowed'] = true;
            }
        }
        return true;
    }
}
