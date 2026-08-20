<?php

class StudentModel {
    public function getStudentInfo() {
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
        return [
            'total' => 8,
            'submitted' => 5,
            'pending' => 3,
            'results_published' => 4
        ];
    }

    public function getAssignments() {
        return [
            ['title' => 'Responsive Web Design', 'course' => 'Web Technology', 'deadline' => '20 August 2026', 'marks' => 20, 'status' => 'Pending'],
            ['title' => 'PHP Form Validation', 'course' => 'Web Technology', 'deadline' => '25 August 2026', 'marks' => 20, 'status' => 'Submitted'],
            ['title' => 'Database Design', 'course' => 'Database', 'deadline' => '28 August 2026', 'marks' => 15, 'status' => 'Pending']
        ];
    }

    public function getResults() {
        return [
            ['title' => 'Responsive Web Design', 'course' => 'Web Technology', 'obtained' => 18, 'total' => 20, 'feedback' => 'Good Work', 'status' => 'Published'],
            ['title' => 'Database Design', 'course' => 'Database', 'obtained' => 13, 'total' => 15, 'feedback' => 'Very Good', 'status' => 'Published']
        ];
    }
}
