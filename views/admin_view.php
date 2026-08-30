<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="views/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-brand">🛡️ Admin Portal</div>
    <div class="nav-user">
        <span>Logged in as: <b>Admin</b></span>
        <a href="index.php?action=logout" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container main-container">
    <h1>Admin Setup & System Reports</h1>

    <!-- System Report -->
    <fieldset>
        <legend>📊 System Report Overview</legend>
        <p>
            <b>Students:</b> <?php echo $report['students']; ?> | 
            <b>Teachers:</b> <?php echo $report['teachers']; ?> | 
            <b>Courses:</b> <?php echo $report['courses']; ?> | 
            <b>Assignments:</b> <?php echo $report['assignments']; ?> | 
            <b>Submissions:</b> <?php echo $report['submissions']; ?>
        </p>
    </fieldset>

    <br>

    <!-- Manage Teachers -->
    <fieldset>
        <legend>👨‍🏫 Manage Teachers</legend>
        <form method="post" action="index.php?action=add_teacher">
            <input type="text" name="name" placeholder="Teacher Name" required>
            <input type="email" name="email" placeholder="Teacher Email" required>
            <input type="text" name="username" placeholder="Teacher ID (e.g. T-102)" required>
            <button type="submit" class="submitButton">Add Teacher</button>
        </form>

        <h4>Teacher List:</h4>
        <ul>
            <?php foreach ($teachers as $t): ?>
                <li><b><?php echo htmlspecialchars($t['name']); ?></b> (<?php echo htmlspecialchars($t['id']); ?>) - <?php echo htmlspecialchars($t['email']); ?></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>

    <br>

    <!-- Course Setup -->
    <fieldset>
        <legend>📚 Course Setup</legend>
        <form method="post" action="index.php?action=setup_course">
            <input type="text" name="code" placeholder="Course Code (e.g. CSC3215)" required>
            <input type="text" name="name" placeholder="Course Name" required>
            <button type="submit" class="submitButton">Add Course</button>
        </form>

        <h4>Active Courses:</h4>
        <ul>
            <?php foreach ($courses as $c): ?>
                <li><b><?php echo htmlspecialchars($c['code']); ?></b>: <?php echo htmlspecialchars($c['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>

</body>
</html>
