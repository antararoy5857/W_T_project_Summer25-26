<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'controllers/AuthController.php';
require_once 'controllers/StudentController.php';
require_once 'controllers/TeacherController.php';
require_once 'controllers/AssignmentController.php';

$page = $_GET['page'] ?? null;
$action = $_GET['action'] ?? null;

// Public auth actions
if ($action === 'login') {
    $auth = new AuthController();
    $auth->login();
    exit;
} else if ($action === 'register') {
    $auth = new AuthController();
    $auth->register();
    exit;
} else if ($action === 'reset_password') {
    $auth = new AuthController();
    $auth->resetPassword();
    exit;
} else if ($action === 'logout') {
    $auth = new AuthController();
    $auth->logout();
    exit;
}

// Public auth views
if ($page === 'register') {
    $auth = new AuthController();
    $auth->showRegister();
    exit;
} else if ($page === 'forgot_password') {
    $auth = new AuthController();
    $auth->showForgotPassword();
    exit;
} else if ($page === 'login') {
    if (isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'] ?? 'student';
        header('Location: index.php?page=' . ($role === 'teacher' ? 'teacher' : 'student'));
        exit;
    }
    $auth = new AuthController();
    $auth->showLogin();
    exit;
}

// Protect all other routes: Redirect to login if user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

// Handle protected actions and views based on user role
$userRole = $_SESSION['user']['role'] ?? 'student';

if ($action === 'create_assignment' || $action === 'publish_marks') {
    if ($userRole !== 'teacher') {
        header('Location: index.php?page=student');
        exit;
    }
    $controller = new AssignmentController();
    if ($action === 'create_assignment') {
        $controller->createAssignment();
    } else {
        $controller->publishMarks();
    }
} else if ($action === 'submit_assignment') {
    if ($userRole !== 'student') {
        header('Location: index.php?page=teacher');
        exit;
    }
    $controller = new AssignmentController();
    $controller->submitAssignment();
} else {
    // Handle protected page views with strict role protection
    switch ($page) {
        case 'teacher':
        case 'teacher_mod':
            if ($userRole !== 'teacher') {
                header('Location: index.php?page=student');
                exit;
            }
            $controller = new TeacherController();
            if ($page === 'teacher_mod') {
                $controller->modification();
            } else {
                $controller->index();
            }
            break;

        case 'student':
        default:
            if ($userRole === 'teacher') {
                header('Location: index.php?page=teacher');
                exit;
            }
            $controller = new StudentController();
            $controller->index();
            break;
    }
}
