<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'controllers/AuthController.php';
require_once 'controllers/StudentController.php';
require_once 'controllers/TeacherController.php';
require_once 'controllers/AssignmentController.php';
require_once 'controllers/AdminController.php';

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
} else if ($action === 'check_email') {
    header('Content-Type: application/json');
    require_once 'models/UserModel.php';
    $userModel = new UserModel();
    $user = $userModel->getUserByUsernameOrEmail($_GET['email'] ?? '');
    echo json_encode(['exists' => $user ? true : false]);
    exit;
} else if ($action === 'get_submissions_ajax') {
    header('Content-Type: application/json');
    require_once 'models/TeacherModel.php';
    $teacherModel = new TeacherModel();
    echo json_encode($teacherModel->getSubmissions());
    exit;
} else if ($action === 'get_student_stats_ajax') {
    header('Content-Type: application/json');
    require_once 'models/StudentModel.php';
    $studentModel = new StudentModel();
    echo json_encode($studentModel->getDashboardStats());
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
        $targetPage = ($role === 'admin') ? 'admin' : (($role === 'teacher') ? 'teacher' : 'student');
        header('Location: index.php?page=' . $targetPage);
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
        header('Location: index.php?page=' . ($userRole === 'admin' ? 'admin' : 'student'));
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
        header('Location: index.php?page=' . ($userRole === 'admin' ? 'admin' : 'teacher'));
        exit;
    }
    $controller = new AssignmentController();
    $controller->submitAssignment();
} else if ($action === 'add_teacher') {
    if ($userRole !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }
    $controller = new AdminController();
    $controller->addTeacher();
} else if ($action === 'setup_course') {
    if ($userRole !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }
    $controller = new AdminController();
    $controller->setupCourse();
} else {
    // Handle protected page views with strict role protection
    switch ($page) {
        case 'admin':
            if ($userRole !== 'admin') {
                $targetPage = ($userRole === 'teacher') ? 'teacher' : 'student';
                header('Location: index.php?page=' . $targetPage);
                exit;
            }
            $controller = new AdminController();
            $controller->index();
            break;

        case 'teacher':
        case 'teacher_mod':
            if ($userRole !== 'teacher') {
                $targetPage = ($userRole === 'admin') ? 'admin' : 'student';
                header('Location: index.php?page=' . $targetPage);
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
            if ($userRole === 'admin') {
                header('Location: index.php?page=admin');
                exit;
            } else if ($userRole === 'teacher') {
                header('Location: index.php?page=teacher');
                exit;
            }
            $controller = new StudentController();
            $controller->index();
            break;
    }
}
