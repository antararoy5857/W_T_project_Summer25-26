<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Module - Student Assignment Management System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body>

<!-- Header & Navigation Bar -->
<div class="navbar">
    <div class="nav-brand">👨‍🏫 Teacher Portal</div>
    <div class="nav-links">
        <a href="index.php?page=teacher" class="active">Standard Panel</a>
        <a href="index.php?page=teacher_mod">Advanced Evaluation Panel</a>
        <a href="index.php?page=student">Student View Preview</a>
    </div>
    <div class="nav-user">
        <span>Logged in as: <b><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Faculty'); ?></b> (Teacher)</span>
        <a href="index.php?action=logout" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container main-container">
    <h1>Teacher Assignment Management Module</h1>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <!-- Feature 1: Create Assignment -->
    <form method="post" action="index.php?action=create_assignment">
        <fieldset>
            <legend>Create & Publish New Course Assignment</legend>

            <div class="form-group">
                <label for="course">Select Course:</label>
                <select id="course" name="course" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['code']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Assignment Title:</label>
                <input type="text" id="title" name="title" placeholder="e.g., Assignment 1 - HTML & CSS Implementation" required>
            </div>

            <div class="form-group">
                <label for="description">Assignment Description / Instructions:</label>
                <textarea id="description" name="description" placeholder="Enter instructions for students..." required></textarea>
            </div>

            <div class="form-group">
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" required>
            </div>

            <div class="form-group">
                <label for="totalMarks">Total Marks:</label>
                <input type="number" id="totalMarks" name="totalMarks" min="1" max="100" placeholder="e.g., 20" required>
            </div>

            <button type="submit" class="submitButton">Create & Publish Assignment</button>
        </fieldset>
    </form>

    <!-- Feature 2 & 3: Review Submissions & Publish Marks -->
    <form method="post" action="index.php?action=publish_marks">
        <fieldset>
            <legend>Review Submissions & Publish Marks</legend>

            <div class="form-group">
                <label for="filterAssignment">Select Assignment to Evaluate:</label>
                <select id="filterAssignment" name="filterAssignment">
                    <option value="1">Assignment 1 - HTML & CSS (Web Technologies)</option>
                    <option value="2">Assignment 2 - PHP Basics (Web Technologies)</option>
                </select>
            </div>

            <table border="1">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Submission Date</th>
                        <th>Submitted File</th>
                        <th>Marks Obtained</th>
                        <th>Teacher Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sub['id']); ?></td>
                        <td><?php echo htmlspecialchars($sub['name']); ?></td>
                        <td><?php echo htmlspecialchars($sub['date']); ?></td>
                        <td><a href="#" onclick="alert('Viewing file: <?php echo htmlspecialchars($sub['file']); ?>'); return false;"><?php echo htmlspecialchars($sub['file']); ?></a></td>
                        <td><input type="number" name="marks[]" min="0" max="20" placeholder="Marks" value="<?php echo htmlspecialchars($sub['marks'] ?? ''); ?>"></td>
                        <td><input type="text" name="feedback[]" placeholder="Good work!" value="<?php echo htmlspecialchars($sub['feedback'] ?? ''); ?>"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit" class="submitButton">Save & Publish Marks</button>
        </fieldset>
    </form>
</div>

</body>
</html>
