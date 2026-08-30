<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Assignment Management System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body class="auth-body">

<div class="auth-card">
    <h2>Academic Login</h2>
    <p class="auth-subtitle">Student Assignment Management System</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <form id="loginForm" method="post" action="index.php?action=login">
        <div class="form-group">
            <label for="username">User ID / Email:</label>
            <input type="text" id="username" name="username" placeholder="e.g. 23-54523-3 or teacher" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <div class="form-group">
            <label for="role">Login As:</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="submitButton width-full">Login to System</button>
    </form>

    <div class="auth-links">
        <a href="index.php?page=register">New User? Register</a> | 
        <a href="index.php?page=forgot_password">Forgot Password?</a>
    </div>

    <div class="demo-credentials">
        <strong>🔑 Quick Faculty Test Credentials:</strong><br>
        • <b>Student:</b> ID: <code>23-54523-3</code> | Pass: <code>123</code><br>
        • <b>Teacher:</b> ID: <code>teacher</code> | Pass: <code>123</code><br>
        • <b>Admin:</b> ID: <code>admin</code> | Pass: <code>123</code>
    </div>
</div>

</body>
</html>
