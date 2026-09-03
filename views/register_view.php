<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Assignment Management System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body class="auth-body">

<div class="auth-card">
    <h2>Create Account</h2>
    <p class="auth-subtitle">Join Assignment Management System</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form id="registerForm" method="post" action="index.php?action=register">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" placeholder="e.g. Md. Shihab Shikdar" required>
        </div>

        <div class="form-group">
            <label for="username">User ID / Student ID / Teacher ID:</label>
            <input type="text" id="username" name="username" placeholder="e.g. 23-99999-3" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="e.g. student@gmail.com" required>
            <div id="emailError" style="color: red; font-size: 13px; margin-top: 5px; display: none;">email already exist try another one</div>
        </div>

        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="Computer Science and Engineering" required>
        </div>

        <div class="form-group">
            <label for="role">Register As:</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <div class="form-group">
            <label for="reg_password">Password:</label>
            <input type="password" id="reg_password" name="password" placeholder="Create a password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
        </div>

        <button type="submit" class="submitButton width-full">Register Account</button>
    </form>

    <div class="auth-links">
        Already have an account? <a href="index.php?page=login">Login Here</a>
    </div>
</div>

</body>
</html>
