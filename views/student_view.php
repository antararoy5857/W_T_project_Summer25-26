<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Panel - Assignment System</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>
<body>

    <!-- Header & Navigation Bar -->
    <div class="navbar">
        <div class="nav-brand">🎓 Student Portal</div>
        <div class="nav-links">
            <a href="index.php?page=student" class="active">Student Panel</a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'teacher'): ?>
                <a href="index.php?page=teacher">Teacher Panel</a>
                <a href="index.php?page=teacher_mod">Teacher Advanced</a>
            <?php endif; ?>
        </div>
        <div class="nav-user">
            <span>Logged in as: <b><?php echo htmlspecialchars($_SESSION['user']['name'] ?? $info['name']); ?></b> (Student)</span>
            <a href="index.php?action=logout" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="main-container">

        <h1>Student Assignment Management System</h1>

        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <!-- Student Information -->
        <fieldset>
            <legend><b>Student Profile Information</b></legend>
            <table cellpadding="8">
                <tr>
                    <td><b>Student Name</b></td>
                    <td>: <?php echo htmlspecialchars($info['name']); ?></td>
                </tr>
                <tr>
                    <td><b>Student ID</b></td>
                    <td>: <?php echo htmlspecialchars($info['id']); ?></td>
                </tr>
                <tr>
                    <td><b>Department</b></td>
                    <td>: <?php echo htmlspecialchars($info['department']); ?></td>
                </tr>
                <tr>
                    <td><b>Role</b></td>
                    <td>: <?php echo htmlspecialchars($info['role'] ?? 'Student'); ?></td>
                </tr>
            </table>
        </fieldset>

        <br>

        <!-- Navigation Menu -->
        <fieldset>
            <legend><b>Quick Navigation</b></legend>
            <table cellpadding="10">
                <tr>
                    <td><a href="#dashboard">Dashboard Overview</a></td>
                    <td><a href="#assignments">View Assignments</a></td>
                    <td><a href="#submit">Submit Assignment</a></td>
                    <td><a href="#results">View Results</a></td>
                    <td><a href="#profile">Full Profile</a></td>
                </tr>
            </table>
        </fieldset>

        <br>

        <!-- Dashboard Stats -->
        <fieldset id="dashboard">
            <legend><b>Dashboard Stats</b></legend>
            <table border="1" cellpadding="12" width="100%">
                <tr>
                    <th>Total Assignments</th>
                    <th>Submitted</th>
                    <th>Pending</th>
                    <th>Results Published</th>
                </tr>
                <tr>
                    <td align="center"><b><?php echo $stats['total']; ?></b></td>
                    <td align="center"><span class="submitted"><?php echo $stats['submitted']; ?></span></td>
                    <td align="center"><span class="pending"><?php echo $stats['pending']; ?></span></td>
                    <td align="center"><span class="result"><?php echo $stats['results_published']; ?></span></td>
                </tr>
            </table>
        </fieldset>

        <br>

        <!-- View Assignments -->
        <fieldset id="assignments">
            <legend><b>Assigned Coursework</b></legend>
            <table border="1" cellpadding="12" width="100%">
                <tr>
                    <th>Assignment Title</th>
                    <th>Course</th>
                    <th>Deadline</th>
                    <th>Total Marks</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($assignments as $asn): ?>
                <tr>
                    <td><b><?php echo htmlspecialchars($asn['title']); ?></b></td>
                    <td><?php echo htmlspecialchars($asn['course']); ?></td>
                    <td><?php echo htmlspecialchars($asn['deadline']); ?></td>
                    <td><?php echo htmlspecialchars($asn['marks']); ?></td>
                    <td class="<?php echo strtolower($asn['status']); ?>"><?php echo htmlspecialchars($asn['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </fieldset>

        <br>

        <!-- Submit Assignment -->
        <fieldset id="submit">
            <legend><b>Submit Assignment Solution</b></legend>
            <form method="post" action="index.php?action=submit_assignment" enctype="multipart/form-data">
                <table cellpadding="10">
                    <tr>
                        <td><b>Select Assignment</b></td>
                        <td>:</td>
                        <td>
                            <select name="assignment" required>
                                <option value="">-- Select Assignment --</option>
                                <?php foreach ($assignments as $asn): ?>
                                    <option value="<?php echo htmlspecialchars($asn['title']); ?>"><?php echo htmlspecialchars($asn['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Upload File</b></td>
                        <td>:</td>
                        <td><input type="file" name="assignmentFile" required></td>
                    </tr>
                    <tr>
                        <td><b>Submission Comment</b></td>
                        <td>:</td>
                        <td><textarea name="comment" rows="3" cols="35" placeholder="Write any notes or comments for your teacher..."></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><input type="submit" value="Submit Assignment" class="submitButton"></td>
                    </tr>
                </table>
            </form>
        </fieldset>

        <br>

        <!-- Results -->
        <fieldset id="results">
            <legend><b>Graded Results & Feedback</b></legend>
            <?php if (empty($results)): ?>
                <p style="padding: 10px; color: #666;">No results published yet.</p>
            <?php else: ?>
                <table border="1" cellpadding="12" width="100%">
                    <tr>
                        <th>Assignment</th>
                        <th>Course</th>
                        <th>Obtained Marks</th>
                        <th>Total Marks</th>
                        <th>Teacher Feedback</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($results as $res): ?>
                    <tr>
                        <td><b><?php echo htmlspecialchars($res['title']); ?></b></td>
                        <td><?php echo htmlspecialchars($res['course']); ?></td>
                        <td><b><?php echo htmlspecialchars($res['obtained']); ?></b></td>
                        <td><?php echo htmlspecialchars($res['total']); ?></td>
                        <td><?php echo htmlspecialchars($res['feedback']); ?></td>
                        <td class="result"><?php echo htmlspecialchars($res['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </fieldset>

        <br>

        <!-- Profile -->
        <fieldset id="profile">
            <legend><b>Contact & Account Info</b></legend>
            <table cellpadding="8">
                <tr>
                    <td><b>Full Name</b></td>
                    <td>: <?php echo htmlspecialchars($info['name']); ?></td>
                </tr>
                <tr>
                    <td><b>Email</b></td>
                    <td>: <?php echo htmlspecialchars($info['email'] ?? 'shihab@gmail.com'); ?></td>
                </tr>
                <tr>
                    <td><b>Phone</b></td>
                    <td>: <?php echo htmlspecialchars($info['phone'] ?? '+880-171717155311'); ?></td>
                </tr>
                <tr>
                    <td><b>Department</b></td>
                    <td>: <?php echo htmlspecialchars($info['department']); ?></td>
                </tr>
            </table>
        </fieldset>

        <br>

        <div class="footer">
            <p>Student Assignment Management System — Web Technology Project</p>
            <p>Role: Student Portal</p>
        </div>

    </div>

</body>
</html>
