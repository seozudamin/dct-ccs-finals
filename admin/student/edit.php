<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';
$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
include '../partials/side-bar.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="col-md-9 col-lg-10">
<div class="container my-5">
    <h2>Edit Student</h2>

    <!-- Form for Editing Student -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="editStudentForm">
                <div class="mb-3">
                    <label for="studentId" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="studentId" name="student_id" readonly  style="background-color: #D3D3D3; color: #646464FF;">
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Student</button>
            </form>
        </div>
    </div>
</div>
</div>