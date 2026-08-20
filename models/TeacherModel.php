<?php

class TeacherModel {
    public function getCourses() {
        return [
            ['code' => 'CSC3215', 'name' => 'CSC 3215: Web Technologies'],
            ['code' => 'CSC2210', 'name' => 'CSC 2210: Object Oriented Programming'],
            ['code' => 'CSC3105', 'name' => 'CSC 3105: Database Systems']
        ];
    }

    public function getSubmissions() {
        return [
            ['id' => '23-54523-3', 'name' => 'Md. Shihab Shikdar', 'date' => '2026-05-10', 'status' => 'On Time', 'file' => 'view_submission.pdf'],
            ['id' => '24-56434-1', 'name' => 'Md Momen Sha', 'date' => '2026-05-12', 'status' => 'Late Submission', 'file' => 'assignment1_momen.zip'],
            ['id' => '22-48652-3', 'name' => 'Antara Roy', 'date' => '2026-05-11', 'status' => 'On Time', 'file' => 'antara_web_task.zip'],
            ['id' => '22-99999-3', 'name' => 'Tanvir Ahmed', 'date' => '—', 'status' => 'Pending', 'file' => 'Not Submitted']
        ];
    }
}
