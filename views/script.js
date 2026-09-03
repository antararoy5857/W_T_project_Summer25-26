// Client-side JavaScript validation and interactive logic for views

document.addEventListener('DOMContentLoaded', function () {

    // 1. Registration Form Validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const pass = document.getElementById('reg_password');
            const confirmPass = document.getElementById('confirm_password');

            if (pass && confirmPass && pass.value !== confirmPass.value) {
                e.preventDefault();
                alert('Validation Error: Passwords do not match!');
                confirmPass.focus();
                return false;
            }
        });
    }

    // 2. Forgot Password Validation
    const forgotForm = document.getElementById('forgotPasswordForm');
    if (forgotForm) {
        forgotForm.addEventListener('submit', function (e) {
            const newPass = document.getElementById('new_password');
            const confirmPass = document.getElementById('confirm_password');

            if (newPass && confirmPass && newPass.value !== confirmPass.value) {
                e.preventDefault();
                alert('Validation Error: New Passwords do not match!');
                confirmPass.focus();
                return false;
            }
        });
    }

    // 3. Student Submission Validation
    const submitForm = document.querySelector('form[action*="submit_assignment"]');
    if (submitForm) {
        submitForm.addEventListener('submit', function (e) {
            const selectAsn = submitForm.querySelector('select[name="assignment"]');
            const fileInput = submitForm.querySelector('input[name="assignmentFile"]');

            if (selectAsn && (selectAsn.value === '' || selectAsn.value === 'Select Assignment')) {
                e.preventDefault();
                alert('Validation Error: Please select an assignment before submitting.');
                selectAsn.focus();
                return false;
            }

            if (fileInput && fileInput.files.length === 0) {
                e.preventDefault();
                alert('Validation Error: Please choose a submission file to upload.');
                fileInput.focus();
                return false;
            }
        });
    }

    // 4. Teacher Assignment Creation Date Validation
    const createForm = document.getElementById('createAssignmentForm') || document.querySelector('form[action*="create_assignment"]');
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            const dueDateInput = createForm.querySelector('#dueDate');
            if (dueDateInput && dueDateInput.value) {
                const dueDate = new Date(dueDateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (dueDate < today) {
                    e.preventDefault();
                    const errDiv = document.getElementById('dateError');
                    if (errDiv) {
                        errDiv.style.display = 'block';
                    } else {
                        alert('Validation Error: Due date cannot be in the past!');
                    }
                    dueDateInput.focus();
                    return false;
                }
            }
        });
    }

    // 5. Mark Bounds Validation (0 - 20)
    const markFields = document.querySelectorAll('.mark-field, input[name="marks[]"]');
    markFields.forEach(input => {
        input.addEventListener('change', function () {
            const val = parseFloat(this.value);
            if (val > 20) {
                alert('Warning: Marks cannot exceed Total Marks (20)!');
                this.value = 20;
            } else if (val < 0) {
                this.value = 0;
            }
        });
    });

    // --- AJAX Request 1: Registration Email Check (XHR) ---
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function () {
            const emailVal = this.value.trim();
            const errDiv = document.getElementById('emailError');
            if (!emailVal) {
                if (errDiv) errDiv.style.display = 'none';
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'index.php?action=check_email&email=' + encodeURIComponent(emailVal), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.exists) {
                        if (errDiv) {
                            errDiv.innerText = 'email already exist try another one';
                            errDiv.style.display = 'block';
                        }
                    } else {
                        if (errDiv) errDiv.style.display = 'none';
                    }
                }
            };
            xhr.send();
        });
    }

    // --- AJAX Request: Registration User ID Check (XHR) ---
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            const usernameVal = this.value.trim();
            const errDiv = document.getElementById('usernameError');
            if (!usernameVal) {
                if (errDiv) errDiv.style.display = 'none';
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'index.php?action=check_username&username=' + encodeURIComponent(usernameVal), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.exists) {
                        if (errDiv) {
                            errDiv.innerText = 'id already exist';
                            errDiv.style.display = 'block';
                        }
                    } else {
                        if (errDiv) errDiv.style.display = 'none';
                    }
                }
            };
            xhr.send();
        });
    }

    // --- AJAX Request 2: Teacher Submissions Loader (XHR) ---
    const btnLoadSubmissions = document.getElementById('btnLoadSubmissionsAjax');
    if (btnLoadSubmissions) {
        btnLoadSubmissions.addEventListener('click', function () {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'index.php?action=get_submissions_ajax', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    const tableBody = document.querySelector('#teacherSubmissionsTable tbody');
                    if (!tableBody) return;
                    tableBody.innerHTML = '';
                    data.forEach(function (sub) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${sub.id}</td>
                            <td>${sub.name}</td>
                            <td>${sub.date}</td>
                            <td><a href="#" onclick="alert('Viewing file: ${sub.file}'); return false;">${sub.file}</a></td>
                            <td><input type="number" name="marks[]" min="0" max="20" placeholder="Marks" value="${sub.marks !== null ? sub.marks : ''}"></td>
                            <td><input type="text" name="feedback[]" placeholder="Good work!" value="${sub.feedback ? sub.feedback : ''}"></td>
                        `;
                        tableBody.appendChild(tr);
                    });
                    alert('Submissions refreshed live via XHR AJAX!');
                }
            };
            xhr.send();
        });
    }

    // --- AJAX Request 3: Student Stats Refresher (XHR) ---
    const btnRefreshStats = document.getElementById('btnRefreshStats');
    if (btnRefreshStats) {
        btnRefreshStats.addEventListener('click', function () {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'index.php?action=get_student_stats_ajax', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (document.getElementById('statTotal')) document.getElementById('statTotal').innerText = data.total;
                    if (document.getElementById('statSubmitted')) document.getElementById('statSubmitted').innerText = data.submitted;
                    if (document.getElementById('statPending')) document.getElementById('statPending').innerText = data.pending;
                    if (document.getElementById('statPublished')) document.getElementById('statPublished').innerText = data.results_published;
                    alert('Student Dashboard Stats refreshed live via XHR AJAX!');
                }
            };
            xhr.send();
        });
    }
});

// 6. Filter Submissions Table (Teacher Panel)
function filterTable() {
    const filterSelect = document.getElementById('filterStatus');
    if (!filterSelect) return;

    const filter = filterSelect.value;
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
