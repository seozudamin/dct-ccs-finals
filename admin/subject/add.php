<?php
include '../partials/header.php';
include '../partials/side-bar.php';// If header.php is in the partials folder at the same level as student// Adjust according to your directory structure
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <form method="post" action="http://dct-ccs-finals.test/admin/dashboard.php">
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

            <!-- Form for Registering New Student -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="studentId" class="form-label"></label>
                            <input type="text" class="form-control" id="studentId" name="student_id"
                                placeholder="Subject Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="firstName" class="form-label"></label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                placeholder="Subject Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register Student</button>
                    </form>
                </div>
            </div>

            <!-- Student List Table -->
            <h3>Student List</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12345</td>
                        <td>English</td>
                        <td>
                            <!-- Edit Button -->
                            <form method="post" action="edit.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="12345">
                                <button type="submit" style="background-color: #05ADADFF; color: white; border: none;"
                                    class="btn btn-sm">Edit</button>
                            </form>
                            <!-- Delete Button -->
                            <form method="post" action="delete.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="12345">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>67890</td>
                        <td>Math</td>
                        <td>
                            <!-- Edit Button -->
                            <form method="post" action="edit.php" style="display:inline;">
                                <button type="submit" style="background-color: #05ADADFF; color: white; border: none;"
                                    class="btn btn-sm">Edit</button>
                            </form>
                            <!-- Delete Button -->
                            <form method="post" action="delete.php" style="display:inline;">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center">No Subject registered yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>