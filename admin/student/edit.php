<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';
$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
include '../partials/side-bar.php';

// Retrieve student data by ID
$student_data = getStudentById($_GET['student_id']);

// Check if the form has been submitted
if (isFormSubmitted()) {
    $student_id = $student_data['student_id'];
    $firstname = isFormSubmitted("first_name");
    $lastname = isFormSubmitted("last_name");

    // Update the student information
    updateStudentDetails($student_id, $firstname, $lastname, 'register.php');
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="col-md-9 col-lg-10">
    <div class="container my-5">
        <h2>Edit Student</h2>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>
        <!-- Form for Editing Student -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="editStudentForm" method="POST"> <!-- Added method POST -->
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="student_id"
                            value="<?php echo htmlspecialchars($student_data['student_id']); ?>" readonly
                            style="background-color: #D3D3D3; color: #646464FF;">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name"
                            value="<?php echo htmlspecialchars($student_data['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name"
                            value="<?php echo htmlspecialchars($student_data['last_name']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </form>
            </div>
        </div>
    </div>
</div>