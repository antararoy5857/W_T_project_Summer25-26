<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Module - Student Assignment Management System</title>
    <script src="views/script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: beige;
            color: #333;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: orchid;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1050px;
            margin: 0 auto;
        }

        fieldset {
            border: 1px solid wheat;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            background-color: whitesmoke;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        legend {
            font-weight: bold;
            color: navy;
            padding: 0 10px;
            font-size: 1.2em;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            padding: 9px;
            border: 1px solid wheat;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: blueviolet;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: beige;
            color: blue;
            font-size: 14px;
        }

        /* Status Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-ontime {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-late {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .btn {
            background-color: blueviolet;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin-right: 10px;
            transition: background 0.2s;
        }

        .btn:hover {
            background-color: powderblue;
            color: #000;
        }

        .btn-publish {
            background-color: green;
        }

        .btn-publish:hover {
            background-color: darkgreen;
            color: white;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .small-input {
            width: 80px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Teacher Module — Assignment Management</h1>
    <p style="text-align: center;"><a href="index.php?page=student">Go to Student Panel</a> | <a href="index.php?page=teacher">Teacher Standard Panel</a></p>

    <!-- 1. Create Assignment Form -->
    <form id="createAssignmentForm" method="post" action="index.php?action=create_assignment" enctype="multipart/form-data">
        <fieldset>
            <legend>Create & Publish New Assignment</legend>

            <div class="form-row">
                <div class="form-group">
                    <label for="course">Select Course (Managed by Admin):</label>
                    <select id="course" name="course" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['code']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="totalMarks">Total Marks:</label>
                    <input type="number" id="totalMarks" name="totalMarks" min="1" max="100" placeholder="e.g., 20" required>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Assignment Title:</label>
                <input type="text" id="title" name="title" placeholder="e.g., Assignment 1 - HTML & CSS Implementation" required>
            </div>

            <div class="form-group">
                <label for="description">Assignment Instructions / Description:</label>
                <textarea id="description" name="description" placeholder="Write detailed instructions for students..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dueDate">Due Date:</label>
                    <input type="date" id="dueDate" name="dueDate" required>
                    <div class="error-message" id="dateError">Due date cannot be in the past!</div>
                </div>

                <!-- Teacher Instruction/Rubric Upload -->
                <div class="form-group">
                    <label for="attachment">Upload Reference/Rubric File (Optional):</label>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.zip">
                </div>
            </div>

            <button type="submit" class="btn">Create Assignment</button>
        </fieldset>
    </form>

    <!-- 2. Review Submissions & Publish Marks Form -->
    <form id="reviewMarksForm" method="post" action="index.php?action=publish_marks">
        <fieldset>
            <legend>Review Student Submissions & Publish Marks</legend>

            <div class="form-row">
                <div class="form-group">
                    <label for="filterAssignment">Select Assignment:</label>
                    <select id="filterAssignment" name="filterAssignment">
                        <option value="1">Assignment 1 - HTML & CSS (Web Technologies)</option>
                        <option value="2">Assignment 2 - PHP Basics (Web Technologies)</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="form-group">
                    <label for="filterStatus">Filter Submissions by Status:</label>
                    <select id="filterStatus" onchange="filterTable()">
                        <option value="all">All Submissions</option>
                        <option value="ontime">On Time</option>
                        <option value="late">Late Submission</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <table id="submissionTable">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Submission Date</th>
                        <th>Submission Status</th>
                        <th>Submitted File</th>
                        <th>Marks Obtained</th>
                        <th>Feedback</th>
                        <th>Allow Re-submit?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $sub):
                        $badgeClass = 'badge-ontime';
                        $rowClass = 'row-ontime';
                        if ($sub['status'] === 'Late Submission') {
                            $badgeClass = 'badge-late';
                            $rowClass = 'row-late';
                        } else if ($sub['status'] === 'Pending') {
                            $badgeClass = 'badge-pending';
                            $rowClass = 'row-pending';
                        }
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td><?php echo htmlspecialchars($sub['id']); ?></td>
                        <td><?php echo htmlspecialchars($sub['name']); ?></td>
                        <td><?php echo htmlspecialchars($sub['date']); ?></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($sub['status']); ?></span></td>
                        <td>
                            <?php if ($sub['file'] !== 'Not Submitted'): ?>
                                <a href="#" target="_blank"><?php echo htmlspecialchars($sub['file']); ?></a>
                            <?php else: ?>
                                <em>Not Submitted</em>
                            <?php endif; ?>
                        </td>
                        <td><input type="number" class="small-input mark-field" name="marks[]" min="0" max="20" placeholder="0-20" <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                        <td><input type="text" name="feedback[]" placeholder="<?php echo ($sub['status'] === 'Pending') ? 'N/A' : 'Feedback'; ?>" <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                        <td style="text-align:center;"><input type="checkbox" name="resubmit[]" value="<?php echo htmlspecialchars($sub['id']); ?>" <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <button type="submit" name="action" value="draft" class="btn">Save as Draft</button>
                    <button type="submit" name="action" value="publish" class="btn btn-publish">Publish Marks</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

<script>
    // 1. Date Validation for Assignment Creation
    document.getElementById('createAssignmentForm').addEventListener('submit', function(event) {
        const dueDateInput = document.getElementById('dueDate').value;
        if (dueDateInput) {
            const dueDate = new Date(dueDateInput);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (dueDate < today) {
                event.preventDefault();
                document.getElementById('dateError').style.display = 'block';
                return;
            }
        }
        document.getElementById('dateError').style.display = 'none';
    });

    // 2. Filter Submissions Table Status-wise
    function filterTable() {
        const filter = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#submissionTable tbody tr');

        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else if (row.classList.contains('row-' + filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // 3. Mark Validation (Warning if obtained marks > max marks)
    document.querySelectorAll('.mark-field').forEach(input => {
        input.addEventListener('change', function() {
            const val = parseFloat(this.value);
            if (val > 20) {
                alert('Warning: Marks cannot exceed Total Marks (20)!');
                this.value = 20;
            } else if (val < 0) {
                this.value = 0;
            }
        });
    });
</script>

</body>
</html>
