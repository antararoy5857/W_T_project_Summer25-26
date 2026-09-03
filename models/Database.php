<?php

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'wt_project';

    private function __construct() {
        // First connect without specifying database to ensure DB exists
        $tempConn = @new mysqli($this->host, $this->user, $this->pass);
        if ($tempConn->connect_error) {
            die("Database Connection Error: " . $tempConn->connect_error);
        }

        // Create database if not exists
        $tempConn->query("CREATE DATABASE IF NOT EXISTS `$this->dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $tempConn->close();

        // Connect to the database
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
        $this->initTables();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function initTables() {
        // Users Table
        $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            name VARCHAR(100) NOT NULL,
            id_code VARCHAR(50) NOT NULL,
            department VARCHAR(100) NOT NULL,
            role ENUM('student', 'teacher', 'admin') NOT NULL DEFAULT 'student',
            phone VARCHAR(30) DEFAULT '',
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        // Courses Table
        $sqlCourses = "CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(20) UNIQUE NOT NULL,
            name VARCHAR(150) NOT NULL,
            credit INT DEFAULT 3,
            semester VARCHAR(50) DEFAULT 'Summer 2025-2026'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        // Assignments Table
        $sqlAssignments = "CREATE TABLE IF NOT EXISTS assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(150) NOT NULL,
            course VARCHAR(150) NOT NULL,
            course_code VARCHAR(20) NOT NULL,
            deadline DATE NOT NULL,
            marks INT NOT NULL DEFAULT 20,
            status VARCHAR(50) DEFAULT 'Pending',
            description TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        // Submissions Table
        $sqlSubmissions = "CREATE TABLE IF NOT EXISTS submissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id VARCHAR(50) NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            assignment_id INT NOT NULL,
            assignment_title VARCHAR(150) NOT NULL,
            submission_date VARCHAR(50) NOT NULL,
            status VARCHAR(50) DEFAULT 'On Time',
            file VARCHAR(255) NOT NULL,
            marks FLOAT DEFAULT NULL,
            feedback TEXT DEFAULT NULL,
            is_graded TINYINT(1) DEFAULT 0,
            resubmit_allowed TINYINT(1) DEFAULT 0,
            UNIQUE KEY unique_student_assignment (student_id, assignment_title)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->conn->query($sqlUsers);
        $this->conn->query($sqlCourses);
        $this->conn->query($sqlAssignments);
        $this->conn->query($sqlSubmissions);

        $this->seedInitialData();
    }

    private function seedInitialData() {
        // Seed Users if empty
        $checkUser = $this->conn->query("SELECT COUNT(*) as cnt FROM users");
        $rowUser = $checkUser->fetch_assoc();
        if ($rowUser['cnt'] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, name, id_code, department, role, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $users = [
                ['23-54523-3', 'shihab@gmail.com', 'Md. Shihab Shikdar', '23-54523-3', 'Computer Science and Engineering', 'student', '+880-171717155311', '123'],
                ['teacher', 'teacher@aiub.edu', 'Dr. Mahfuzur Rahman', 'T-101', 'Computer Science and Engineering', 'teacher', '+880-1818181818', '123'],
                ['admin', 'admin@aiub.edu', 'System Administrator', 'ADM-01', 'Administration', 'admin', '+880-1999999999', '123'],
                ['24-56434-1', 'momen@gmail.com', 'Md Momen Sha', '24-56434-1', 'Computer Science and Engineering', 'student', '+880-1700000001', '123'],
                ['22-48652-3', 'antara@gmail.com', 'Antara Roy', '22-48652-3', 'Computer Science and Engineering', 'student', '+880-1700000002', '123'],
                ['22-99999-3', 'tanvir@gmail.com', 'Tanvir Ahmed', '22-99999-3', 'Computer Science and Engineering', 'student', '+880-1700000003', '123']
            ];

            foreach ($users as $u) {
                $stmt->bind_param("ssssssss", $u[0], $u[1], $u[2], $u[3], $u[4], $u[5], $u[6], $u[7]);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Seed Courses if empty
        $checkCourse = $this->conn->query("SELECT COUNT(*) as cnt FROM courses");
        $rowCourse = $checkCourse->fetch_assoc();
        if ($rowCourse['cnt'] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO courses (code, name, credit, semester) VALUES (?, ?, ?, ?)");
            $courses = [
                ['CSC3215', 'CSC 3215: Web Technologies', 3, 'Summer 2025-2026'],
                ['CSC2210', 'CSC 2210: Object Oriented Programming', 3, 'Summer 2025-2026'],
                ['CSC3105', 'CSC 3105: Database Systems', 3, 'Summer 2025-2026']
            ];
            foreach ($courses as $c) {
                $stmt->bind_param("ssis", $c[0], $c[1], $c[2], $c[3]);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Seed Assignments if empty
        $checkAssn = $this->conn->query("SELECT COUNT(*) as cnt FROM assignments");
        $rowAssn = $checkAssn->fetch_assoc();
        if ($rowAssn['cnt'] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO assignments (id, title, course, course_code, deadline, marks, status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $assignments = [
                [1, 'Responsive Web Design', 'CSC 3215: Web Technologies', 'CSC3215', '2026-08-20', 20, 'Pending', 'Create a fully responsive layout using flexbox and grid.'],
                [2, 'PHP Form Validation', 'CSC 3215: Web Technologies', 'CSC3215', '2026-08-25', 20, 'Pending', 'Implement server-side & client-side PHP form validation.'],
                [3, 'Database Schema Design', 'CSC 3105: Database Systems', 'CSC3105', '2026-08-28', 15, 'Pending', 'Design ER diagram and normalized relational tables.']
            ];
            foreach ($assignments as $a) {
                $stmt->bind_param("issssiss", $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Seed Submissions if empty
        $checkSub = $this->conn->query("SELECT COUNT(*) as cnt FROM submissions");
        $rowSub = $checkSub->fetch_assoc();
        if ($rowSub['cnt'] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO submissions (student_id, student_name, assignment_id, assignment_title, submission_date, status, file, marks, feedback, is_graded, resubmit_allowed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $submissions = [
                ['23-54523-3', 'Md. Shihab Shikdar', 2, 'PHP Form Validation', '2026-08-24', 'On Time', 'shihab_php_val.zip', 18.0, 'Good Work', 1, 0],
                ['24-56434-1', 'Md Momen Sha', 1, 'Responsive Web Design', '2026-08-22', 'Late Submission', 'assignment1_momen.zip', null, '', 0, 0],
                ['22-48652-3', 'Antara Roy', 1, 'Responsive Web Design', '2026-08-19', 'On Time', 'antara_web_task.zip', 19.0, 'Excellent design!', 1, 0],
                ['22-99999-3', 'Tanvir Ahmed', 1, 'Responsive Web Design', '—', 'Pending', 'Not Submitted', null, '', 0, 0]
            ];
            foreach ($submissions as $s) {
                $stmt->bind_param("ssissssdsii", $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $s[6], $s[7], $s[8], $s[9], $s[10]);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
}
