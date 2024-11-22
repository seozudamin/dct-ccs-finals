<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<div class="col-md-9 col-lg-10">
    <h3 class="text-left mb-5 mt-5">Delete Subject</h3>

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="add.php">Add subject</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
        </ol>
    </nav>
    <div class="border p-5">
        <!-- Confirmation Message -->
        <p class="text-left">Are you sure you want to delete the following subject record?</p>
        <ul class="text-left">
            <li><strong>Subject Code:</strong></li>
            <li><strong>Subject Name:</strong></li>
        </ul>

        <!-- Confirmation Form -->
        <form method="POST" class="text-left">
            <a href="add.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Delete Subject Record</button>
        </form>
    </div>
</div>

<?php
include '../partials/footer.php';
?>