<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<div class="container mt-5">
        <h3 class="text-left mb-5 mt-5">Delete a Student</h3>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>

        <!-- Confirmation Message -->
        <div class="border p-5">
            <h4 class="text-left mb-2 mt-5">Selected Student Information</h4>
            <ul class="text-left">
                <li><strong>Student ID:</strong> [Student ID]</li>
                <li><strong>Name:</strong> [First Name] [Last Name]</li>
            </ul>
            <hr>

            <!-- Confirmation Form -->
            <form method="POST" class="text-left">
                <!-- Placeholder for subject checkboxes -->
                [Subject Checkboxes]

                <button type="submit" class="btn btn-primary mt-3">Attach Subjects</button>
            </form>
        </div>

        <!-- Subject List -->
        <div class="card p-4 mt-5 mb-5">
            <h3 class="card-title text-left">Subject List</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Placeholder for subject rows -->
                    [Subject Rows]
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
