<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Module - Student Assignment Management System</title>
    <script src="views/script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: beige;
            color: black;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
            color: orchid;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        fieldset {
            border: 1px solid beige;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
            background-color: whitesmoke;
        }

        legend {
            font-weight: bold;
            color: navy;
            padding: 0 10px;
            font-size: 1.1em;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            padding: 8px;
            border: 1px solid wheat;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid beige;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: beige;
            color: blue;
        }

        .btn {
            background-color: blueviolet;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: powderblue;
        }

        .btn-publish {
            background-color: blueviolet ;
        }

        .btn-publish:hover {
            background-color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Teacher Module</h1>
    <p style="text-align: center;"><a href="index.php?page=student">Go to Student Panel</a> | <a href="index.php?page=teacher_mod">Teacher Advanced Panel</a></p>

    <!-- Feature 1: Create Assignment -->
    <form method="post" action="index.php?action=create_assignment">
        <fieldset>
            <legend>Create Assignment</legend>

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
                <input type="text" id="title" name="title" placeholder="e.g., Assignment 1 - HTML & CSS" required>
            </div>

            <div class="form-group">
                <label for="description">Assignment Description:</label>
                <textarea id="description" name="description" placeholder="Enter assignment instructions..." required></textarea>
            </div>

            <div class="form-group">
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" required>
            </div>

            <div class="form-group">
                <label for="totalMarks">Total Marks:</label>
                <input type="number" id="totalMarks" name="totalMarks" min="1" max="100" placeholder="e.g., 20" required>
            </div>

            <button type="submit" class="btn">Create & Publish Assignment</button>
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

            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Submission Date</th>
                        <th>Submitted File</th>
                        <th>Marks Obtained</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sub['id']); ?></td>
                        <td><?php echo htmlspecialchars($sub['name']); ?></td>
                        <td><?php echo htmlspecialchars($sub['date']); ?></td>
                        <td><a href="#" target="_blank"><?php echo htmlspecialchars($sub['file']); ?></a></td>
                        <td><input type="number" name="marks[]" min="0" max="20" placeholder="Marks"></td>
                        <td><input type="text" name="feedback[]" placeholder="Good work!"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit" class="btn btn-publish">Save & Publish Marks</button>
        </fieldset>
    </form>
</div>

</body>
</html>
