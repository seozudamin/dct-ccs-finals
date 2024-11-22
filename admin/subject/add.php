<?php
include '../partials/header.php';
include '../partials/side-bar.php';
include '../../functions.php'; // Include the functions

$link = "http://dct-ccs-finals.test/admin/dashboard.php"; // Update this link as needed

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

<?php
if (isFormSubmitted()) {
    $subject_code = postData("student_id"); // This should match the input name in your form
    $subject_name = postData("first_name"); // This should match the input name in your form

    // Debugging: Check if the data is being received
    if ($subject_code && $subject_name) {
        addSubject($subject_code, $subject_name); // Call the function to add the subject
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
$subjects = fetchCourses();
?>
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <form method="post" action="<?= $link ?>">
                            <button type="submit" class="btn btn-link"
                                style="border: none; background: none; padding: 0; text-decoration: underline; cursor: pointer;">Dashboard</button>
                        </form>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                </ol>
            </nav>
            <h2>Add a New Subject</h2>

            <!-- Display Errors -->
            <div class="alert alert-danger" style="display:none;">
                <ul>
                    <!-- Error messages will go here if needed -->
                    <li>Example error message</li>
                </ul>
            </div>

            <!-- Form for Adding New Subject -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="studentId" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="studentId" name="student_id" placeholder="Subject Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="firstName" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Subject Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </form>
                </div>
            </div>

            <!-- Subject List Table -->
            <h3>Subject List</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($subjects)): ?>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                                <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <form method="post" action="edit.php" style="display:inline;">
                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars($subject['subject_code']) ?>">
                                        <button type="submit" style="background-color: #05ADADFF; color: white; border: none;" class="btn btn-sm">Edit</button>
                                    </form>
                                    <!-- Delete Button -->
                                    <form method="post" action="delete.php" style="display:inline;">
                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars($subject['subject_code']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No subjects registered yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>