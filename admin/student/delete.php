<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <div class="container mt-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>

        <!-- Delete Confirmation Card -->
        <div class="card">
            <div class="card-header">
                <h4>Delete a Student</h4>
            </div>
            <div class="card-body">
                <p>Are you sure you want to delete the following student record?</p>
                <ul>
                    <li><strong>Student ID:</strong> [Student ID]</li>
                    <li><strong>First Name:</strong> [First Name]</li>
                    <li><strong>Last Name:</strong> [Last Name]</li>
                </ul>
                <!-- Action Buttons -->
                <form method="POST">
                    <input type="hidden" name="student_id" value="[Student ID]">
                    <div class="d-flex gap-2">
                        <a href="register.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="confirm_delete" class="btn btn-primary">Delete Student Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous></script>