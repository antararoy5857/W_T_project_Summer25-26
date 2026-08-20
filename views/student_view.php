<!doctype html>
<html>

<head>
    <title>Student Panel</title>
    <link rel="stylesheet" href="views/style.css">
    <script src="views/script.js" defer></script>
</head>

<body>

    <h1>Student Assignment Management System</h1>
    <p style="text-align: center;"><a href="index.php?page=teacher">Go to Teacher Panel</a> | <a href="index.php?page=teacher_mod">Teacher Advanced Panel</a></p>

    <hr>

    <!-- Student Information -->
    <fieldset>
        <legend><b>Student Information</b></legend>
        <table cellpadding="10">
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
                <td>: <?php echo htmlspecialchars($info['role']); ?></td>
            </tr>
        </table>
    </fieldset>

    <br>

    <!-- Navigation -->
    <fieldset>
        <legend><b>Student Menu</b></legend>
        <table cellpadding="10">
            <tr>
                <td><a href="#dashboard">Dashboard</a></td>
                <td><a href="#assignments">View Assignments</a></td>
                <td><a href="#submit">Submit Assignment</a></td>
                <td><a href="#results">View Results</a></td>
                <td><a href="#profile">Profile</a></td>
            </tr>
        </table>
    </fieldset>

    <br>

    <!-- Dashboard -->
    <fieldset id="dashboard">
        <legend><b>Dashboard</b></legend>
        <table border="1" cellpadding="15" width="80%">
            <tr>
                <th>Total Assignments</th>
                <th>Submitted</th>
                <th>Pending</th>
                <th>Results Published</th>
            </tr>
            <tr>
                <td align="center"><?php echo $stats['total']; ?></td>
                <td align="center"><?php echo $stats['submitted']; ?></td>
                <td align="center"><?php echo $stats['pending']; ?></td>
                <td align="center"><?php echo $stats['results_published']; ?></td>
            </tr>
        </table>
    </fieldset>

    <br>

    <!-- View Assignments -->
    <fieldset id="assignments">
        <legend><b>View Assignments</b></legend>
        <table border="1" cellpadding="12" width="100%">
            <tr>
                <th>Assignment</th>
                <th>Course</th>
                <th>Deadline</th>
                <th>Total Marks</th>
                <th>Status</th>
            </tr>
            <?php foreach ($assignments as $asn): ?>
            <tr>
                <td><?php echo htmlspecialchars($asn['title']); ?></td>
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
        <legend><b>Submit Assignment</b></legend>
        <form method="post" action="index.php?action=submit_assignment" enctype="multipart/form-data">
            <table cellpadding="10">
                <tr>
                    <td><b>Select Assignment</b></td>
                    <td>:</td>
                    <td>
                        <select name="assignment">
                            <option>Select Assignment</option>
                            <?php foreach ($assignments as $asn): ?>
                                <option><?php echo htmlspecialchars($asn['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><b>Upload File</b></td>
                    <td>:</td>
                    <td><input type="file" name="assignmentFile"></td>
                </tr>
                <tr>
                    <td><b>Comment</b></td>
                    <td>:</td>
                    <td><textarea name="comment" rows="4" cols="35" placeholder="Write your comment"></textarea></td>
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
        <legend><b>View Results</b></legend>
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
                <td><?php echo htmlspecialchars($res['title']); ?></td>
                <td><?php echo htmlspecialchars($res['course']); ?></td>
                <td><?php echo htmlspecialchars($res['obtained']); ?></td>
                <td><?php echo htmlspecialchars($res['total']); ?></td>
                <td><?php echo htmlspecialchars($res['feedback']); ?></td>
                <td class="result"><?php echo htmlspecialchars($res['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </fieldset>

    <br>

    <!-- Profile -->
    <fieldset id="profile">
        <legend><b>Student Profile</b></legend>
        <table cellpadding="10">
            <tr>
                <td><b>Name</b></td>
                <td>: <?php echo htmlspecialchars($info['name']); ?></td>
            </tr>
            <tr>
                <td><b>Email</b></td>
                <td>: <?php echo htmlspecialchars($info['email']); ?></td>
            </tr>
            <tr>
                <td><b>Phone</b></td>
                <td>: <?php echo htmlspecialchars($info['phone']); ?></td>
            </tr>
            <tr>
                <td><b>Department</b></td>
                <td>: <?php echo htmlspecialchars($info['department']); ?></td>
            </tr>
        </table>
    </fieldset>

    <br>

    <div class="footer">
        <p>Student Assignment Management System</p>
        <p>Student Panel</p>
    </div>

</body>
</html>
