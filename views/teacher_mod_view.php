<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Advanced Module - Student Assignment Management System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body>

<!-- Header & Navigation Bar -->
<div class="navbar">
    <div class="nav-brand">👨‍🏫 Teacher Portal (Advanced)</div>
    <div class="nav-links">
        <a href="index.php?page=teacher">Standard Panel</a>
        <a href="index.php?page=teacher_mod" class="active">Advanced Evaluation Panel</a>
    </div>
    <div class="nav-user">
        <span>Logged in as: <b><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Faculty'); ?></b> (Teacher)</span>
        <a href="index.php?action=logout" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container main-container">
    <h1>Teacher Module — Advanced Assignment Evaluation</h1>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

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
                    <div class="error-message" id="dateError" style="display:none; color:red; font-size:12px;">Due date cannot be in the past!</div>
                </div>

                <!-- Teacher Instruction/Rubric Upload -->
                <div class="form-group">
                    <label for="attachment">Upload Reference/Rubric File (Optional):</label>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.zip">
                </div>
            </div>

            <button type="submit" class="submitButton">Create Assignment</button>
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
                                <a href="#" onclick="alert('Viewing submission file: <?php echo htmlspecialchars($sub['file']); ?>'); return false;"><?php echo htmlspecialchars($sub['file']); ?></a>
                            <?php else: ?>
                                <em>Not Submitted</em>
                            <?php endif; ?>
                        </td>
                        <td><input type="number" class="small-input mark-field" name="marks[]" min="0" max="20" placeholder="0-20" value="<?php echo htmlspecialchars($sub['marks'] ?? ''); ?>" <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                        <td><input type="text" name="feedback[]" placeholder="<?php echo ($sub['status'] === 'Pending') ? 'N/A' : 'Feedback'; ?>" value="<?php echo htmlspecialchars($sub['feedback'] ?? ''); ?>" <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                        <td style="text-align:center;"><input type="checkbox" name="resubmit[]" value="<?php echo htmlspecialchars($sub['id']); ?>" <?php if (!empty($sub['resubmit_allowed'])) echo 'checked'; ?> <?php if ($sub['status'] === 'Pending') echo 'disabled'; ?>></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <button type="submit" name="action" value="publish" class="submitButton btn-publish">Save & Publish Marks</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

</body>
</html>
