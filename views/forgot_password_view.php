<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Student Assignment Management System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body class="auth-body">

<div class="auth-card">
    <h2>Reset Password</h2>
    <p class="auth-subtitle">Recover access to your account</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form id="forgotPasswordForm" method="post" action="index.php?action=reset_password">
        <div class="form-group">
            <label for="username">User ID / Email:</label>
            <input type="text" id="username" name="username" placeholder="Enter your ID or Email" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="submitButton width-full">Update Password</button>
    </form>

    <div class="auth-links">
        Remembered your password? <a href="index.php?page=login">Back to Login</a>
    </div>
</div>

</body>
</html>
