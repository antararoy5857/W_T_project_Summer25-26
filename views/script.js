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
